<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.SET_SALES_TARGET')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'url' => '', 'id' =>'saveSalesTargetFrom', 'class' => 'form-horizontal')) !!}
    {{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                @lang('label.SALES_PERSON') : <strong>{!! !empty($salesPersonList)?$salesPersonList[$request->sales_person_id]:'' !!}</strong>
            </div>
        </div>
        <br/>
        <div class="row">
            {!! Form::hidden('sales_person_id', $request->sales_person_id, ['id'=> 'salesPersonId']) !!}
            {!! Form::hidden('view_id', $request->view_id, ['id'=> 'viewId']) !!}
            <label class="col-md-3 label-month-picker" for="effectiveMonth">@lang('label.EFFECTIVE_FOR') :</label>
            <div class="col-md-6 col-month-picker">
                <div class="input-group date month-picker" data-date-format="M yyyy" data-date-viewmode="years" data-date-minviewmode="months">
                    {!! Form::text('effective_month', date('F Y'), ['id'=> 'effectiveMonth', 'class' => 'form-control', 'placeholder' => 'M yyyy', 'readonly' => '']) !!} 
                    <span class="input-group-btn">
                        <button class="btn default reset-date" type="button" remove="effectiveMonth">
                            <i class="fa fa-times"></i>
                        </button>
                        <button class="btn default date-set" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div id="getSalesTarget">
            <hr/>
            <div class="row">
                <div class="col-md-5">
                    @lang('label.EFFECTIVE_DATE') : <strong>{!! Helper::formatDate($effectiveDate) !!}</strong>
                </div>
                <div class="col-md-5">
                    @lang('label.DEADLINE') : <strong>{!! Helper::formatDate($deadline) !!}</strong>
                </div>
                <?php $disabled = ''; ?>
                @if(!empty($salesTarget) && $salesTarget->lock_status == '1')
                <?php
                $disabled = 'disabled';
                ?>
                <div class="col-md-2">
                    <span class="label label-danger pull-right"><i class="fa fa-lock"></i>&nbsp;@lang('label.LOCKED')</span>
                </div>
                @endif
            </div>
            <br />
            <div class="row">
                <div class="table-responsive col-md-12 webkit-scrollbar">
                    <table class="table table-bordered table-hover module-access-view" id="headerFix">
                        <thead>
                            <tr class="info">
                                <th  class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th  class="vcenter">@lang('label.PRODUCT')</th>
                                <th  class="text-center vcenter">@lang('label.QUANTITY')</th>
                                <th class="text-center vcenter">@lang('label.REMARKS')</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if(!$productList->isEmpty())
                            <?php $sl = 0; ?>
                            @foreach($productList as $product)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $product->name !!}</td>
                                <td class="text-center vcenter width-150">
                                    <div class="input-group bootstrap-touchspin width-inherit">
                                        {!! Form::text('quantity['.$product->id.']', !empty($quantity[$product->id])?$quantity[$product->id]:null, ['id'=> 'quantity_'.$product->id, 'class' => 'form-control integer-decimal-only text-right text-input-width-100-per product-quantity', $disabled]) !!}
                                        <span class="input-group-addon bootstrap-touchspin-postfix">{!! $product->measure_unit_name !!}</span>
                                    </div>
                                </td>
                                <td class="text-center vcenter">
                                    {{ Form::textarea('remarks['.$product->id.']', !empty($remarks[$product->id])?$remarks[$product->id]:null, ['id'=> 'remarks_'.$product->id, 'class' => 'form-control text-input-width','size' => '10x2', $disabled]) }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="text-right vcenter"><strong>@lang('label.TOTAL_QUANTITY')</strong></td>
                                <td class="vcenter text-right total-quantity"><strong>{!! !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0 !!}</strong></td>
                                {!! Form::hidden('total_quantity', !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0.00, ['id' => 'totalQuantity']) !!}
                                <td></td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="4" class="vcenter text-danger">@lang('label.NO_RELATED_PRODUCT_FOUND_FOR_THIS_SALES_PERSON')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal-footer">
        <div id="setSubmitLockbtn">
            @if(!$productList->isEmpty())
            <button type="button" class="btn btn-primary" id="saveSalesTarget" {{ $disabled }}>@lang('label.CONFIRM_SUBMIT')</button>
            @if(!empty($userAccessArr[20][10]))
            <button type="button" class="btn purple-sharp" id="lockSalesTarget" {{ $disabled }}>@lang('label.SAVE_AND_LOCK')</button>
            @endif
            @endif
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $("#headerFix").tableHeadFixer();
    $('.month-picker').datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        startDate: new Date(),
    });

    $("#dataTable").tableHeadFixer();
    $("#effectiveMonth").on("change", function () {
        var effectiveMonth = $(this).val();
        var salesPersonId = $("#salesPersonId").val();
        $.ajax({
            url: '{{URL::to("salesTarget/getSalesTarget/")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                sales_person_id: salesPersonId,
                effective_month: effectiveMonth,
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#getSalesTarget').html(res.html);
                $('#setSubmitLockbtn').html(res.setsubmitLock);
                App.unblockUI();
            },
        });
    });

    //total quantity
    $(".product-quantity").each(function () {
        $(this).on("keyup", function () {
            var totalQuantity = 0;
            $(".product-quantity").each(function () {
                var val = $(this).val();
                if (val == '') {
                    val = 0;
                }
                totalQuantity += parseInt(val);
            });
            //alert(totalQuantity);
            $("td.total-quantity strong").html(totalQuantity);
            $("#totalQuantity").val(totalQuantity);
        });
    });


});
</script>
