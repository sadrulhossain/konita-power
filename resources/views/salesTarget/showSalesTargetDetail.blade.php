<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.VIEW_SALES_TARGET')
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
            <label class="col-md-3 label-month-picker" for="effectiveMonthForDetail">@lang('label.EFFECTIVE_FOR') :</label>
            <div class="col-md-6 col-month-picker">
                <div class="input-group date month-picker" data-date-format="M yyyy" data-date-viewmode="years" data-date-minviewmode="months">

                    {!! Form::text('effective_month', date('F Y'), ['id'=> 'effectiveMonthForDetail', 'class' => 'form-control', 'placeholder' => 'M yyyy', 'readonly' => '']) !!} 
                    <span class="input-group-btn">
                        <button class="btn default reset-date" type="button" remove="effectiveMonthForDetail">
                            <i class="fa fa-times"></i>
                        </button>
                        <button class="btn default date-set" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div id="getSalesTargetDetail">
            <hr/>
            @if(!$productList->isEmpty())
            <div class="row">
                <div class="col-md-5">
                    @lang('label.EFFECTIVE_DATE') : <strong>{!! Helper::formatDate($effectiveDate) !!}</strong>
                </div>
                <div class="col-md-5">
                    @lang('label.DEADLINE') : <strong>{!! Helper::formatDate($deadline) !!}</strong>
                </div>
                @if(!empty($salesTarget) && $salesTarget->lock_status == '1')
                <div class="col-md-2">
                    <span class="label label-danger pull-right"><i class="fa fa-lock"></i>&nbsp;@lang('label.LOCKED')</span>
                </div>
                @endif
            </div>
            <br />
            <div class="row">
                <div class="table-responsive col-md-12 webkit-scrollbar" style="max-height: 350px;">
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
                            <?php $sl = 0; ?>
                            @foreach($productList as $product)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $product->name !!}</td>
                                <td class="text-right vcenter">
                                    {!! !empty($quantity[$product->id])?$quantity[$product->id]:0 !!}&nbsp;{!! $product->measure_unit_name !!}
                                </td>
                                <td class=" vcenter">
                                    {!! !empty($remarks[$product->id])?$remarks[$product->id]:'' !!}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="text-right vcenter"><strong>@lang('label.TOTAL_QUANTITY')</strong></td>
                                <td class="vcenter text-right total-quantity"><strong>{!! !empty($salesTarget->total_quantity)?$salesTarget->total_quantity:0 !!}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="alert alert-danger">
                        <p>
                            <i class="fa fa-warning"></i>
                            @lang('label.SALES_TARGET_IS_NOT_SET_YET_FOR_THIS_MONTH', ['month' => date('F Y') ]).
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

    </div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();
    $("#headerFix").tableHeadFixer({
        left: 2
    });
    $('.month-picker').datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
    });
    $("#dataTable").tableHeadFixer();





});
</script>
