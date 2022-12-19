<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.INQUIRY_REASSIGNMENT')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'inquiryReassignmentForm')) !!}
    {!! Form::hidden('inquiry_id', $request->inquiry_id) !!}

    {{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="salesPersonId">@lang('label.NEW_SALES_PERSON') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('sales_person_id', $salesPersonList, null, ['class' => 'form-control js-source-states ','id'=>'salesPersonId']) !!}
                                    <span class="text-danger">{{ $errors->first('sales_person_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row div-box-default margin-top-20">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 border-bottom-1-green-seagreen">
                                <h4><strong>@lang('label.BASIC_INQUIRY_INFORMATION')</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <table class="table table-borderless">
                                    <tr >
                                        <td class="bold" width="50%">@lang('label.BUYER_NAME')</td>
                                        <td width="50%">{!! !empty($inquiryInfo->buyer_name)?$inquiryInfo->buyer_name:'' !!}</td>
                                    </tr>
                                    <tr >
                                        <td class="bold" width="50%">@lang('label.SALES_PERSON')</td>
                                        <td width="50%">{!! !empty($inquiryInfo->sales_person_name)?$inquiryInfo->sales_person_name:'' !!}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <table class="table table-borderless">
                                    <tr >
                                        <td class="bold" width="50%">@lang('label.INQUIRY_DATE')</td>
                                        <td width="50%">{!! !empty($inquiryInfo->creation_date)?Helper::formatDate($inquiryInfo->creation_date):'' !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold" width="50%">@lang('label.STATUS')</td>
                                        <td width="50%">
                                            @if($inquiryInfo->order_status == '0')
                                            <span class="label label-sm label-primary">@lang('label.INQUIRY')</span>
                                            @elseif($inquiryInfo->order_status == '1')
                                            <span class="label label-sm label-info">@lang('label.PENDING')</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row padding-2 margin-top-10">
                    <div class="col-md-12">
                        <div class="table-responsive webkit-scrollbar">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="vcenter">@lang('label.PRODUCT')</th>
                                        <th class="vcenter">@lang('label.BRAND')</th>
                                        <th class="vcenter">@lang('label.GRADE')</th>
                                        <th class="vcenter">@lang('label.GSM')</th>
                                        <th class="vcenter text-center">@lang('label.QUANTITY')</th>
                                        <th class="vcenter text-center">@lang('label.UNIT_PRICE')</th>
                                        <th class="vcenter text-center">@lang('label.TOTAL_PRICE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$inquiryDetailsInfo->isEmpty())
                                    @foreach($inquiryDetailsInfo as $item)
                                    <?php
                                    $unit = !empty($item->unit_name) ? ' ' . $item->unit_name : '';
                                    $perUnit = !empty($item->unit_name) ? ' / ' . $item->unit_name : '';
                                    ?>
                                    <tr>
                                        <td class="vcenter">{{ !empty($item->product_name)?$item->product_name:'' }}</td>
                                        <td class="vcenter">{{ !empty($item->brand_name)?$item->brand_name:'' }}</td>
                                        <td class="vcenter">{{ !empty($item->grade_name)?$item->grade_name:'' }}</td>
                                        <td class="vcenter">{{ !empty($item->gsm)?$item->gsm:'' }}</td>
                                        <td class="vcenter text-right">
                                            {{ !empty($item->quantity) ? $item->quantity . $unit : __('label.N_A') }}
                                        </td>
                                        <td class="vcenter text-right">
                                            {{ !empty($item->unit_price) ? '$' . $item->unit_price . $perUnit : __('label.N_A') }}
                                        </td>
                                        <td class="vcenter text-right">
                                            {{ !empty($item->total_price) ? '$' . $item->total_price : __('label.N_A') }}
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
                <button class="btn btn-inline green" type="button" id='submitinquiryReassignment'>
                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
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
