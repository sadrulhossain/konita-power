<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INQUIRY_CONFIRMATION')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'confirmationForm')) !!}
                {!! Form::hidden('inquiry_id', $target->id) !!}

                {{csrf_field()}}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="name">@lang('label.BUYER') :</label>
                                <div class="col-md-8 bold margin-top-10">
                                    {{$target->buyerName}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="poNo">@lang('label.PO_NO') :</label>
                                <div class="col-md-8">
                                    {!! Form::text('purchase_order_no',!empty($target->purchase_order_no)?$target->purchase_order_no:$poGenerate, ['id'=> 'poNo', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('purchase_order_no') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="poDate">@lang('label.PO_DATE') :</label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker2">
                                        {!! Form::text('po_date', !empty($target->po_date)?Helper::formatDate($target->po_date):null, ['id'=> 'poDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="poDate">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-danger">{{ $errors->first('po_date') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--product details-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="vcenter">@lang('label.PRODUCT')</th>
                                            <th class="vcenter">@lang('label.BRAND')</th>
                                            <th class="vcenter">@lang('label.GRADE')</th>
                                            <th class="vcenter">@lang('label.GSM')</th>
                                            <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                            <th class="vcenter text-center">@lang('label.UNIT_PRICE')<span class="text-danger"> *</span></th>
                                            <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$inquiryDetails->isEmpty())
                                        @foreach($inquiryDetails as $item)
                                        <tr>
                                            <td class="vcenter">
                                                {{!empty($item->product_name)?$item->product_name:''}}
                                            </td>
                                            <td class="vcenter">{{!empty($item->brand_name)?$item->brand_name:''}}</td>
                                            <td class="vcenter">{{!empty($item->grade_name)?$item->grade_name:''}}</td>
                                            <td class="vcenter">{{!empty($item->gsm)?$item->gsm:''}}</td>
                                            <td class="vcenter text-right">
                                                <div class="input-group bootstrap-touchspin">
                                                    {!! Form::text('quantity['.$item->id.']', !empty($item->unit_price)?$item->quantity:null, ['id'=> 'quantity', 'class' => 'form-control text-right','readonly']) !!} 
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">{!! !empty($item->unit_name)?' '.$item->unit_name:'' !!}</span>
                                                </div>
                                            </td>
                                            <td class="vcenter">
                                                <div class="input-group bootstrap-touchspin">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('unit_price['.$item->id.']', !empty($item->unit_price)?$item->unit_price:null, ['class' => 'form-control  integer-decimal-only text-right unit-price','data-quantity'=>$item->quantity,'data-id'=>$item->id]) !!} 
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">{!! !empty($item->unit_name)?'/'.' '.$item->unit_name:'' !!}</span>
                                                </div>
                                            </td>
                                            <td class="vcenter">
                                                <div class="input-group bootstrap-touchspin">
                                                    <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                    {!! Form::text('total_price['.$item->id.']',!empty($item->total_price)?$item->total_price: null, ['id'=> 'totalPrice_'.$item->id, 'class' => 'form-control text-right','readonly']) !!} 
                                                </div>
                                                <span class="text-danger">{{ $errors->first('total_price') }}</span>
                                            </td>

                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-12"> 
                    <button class="btn btn-inline green" type="button" id='submitConfirmation'>
                        <i class="fa fa-check"></i> @lang('label.SUBMIT')
                    </button> 
                    <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $('.unit-price').keyup(function (e) {
        var totalPrice = 0;
        var totalQuantity = $(this).attr('data-quantity');
        var inquiryDetailsId = $(this).attr('data-id');


        totalPrice = totalQuantity * $(this).val();
        $('#totalPrice_' + inquiryDetailsId).val(totalPrice.toFixed(2));



    });




</script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
