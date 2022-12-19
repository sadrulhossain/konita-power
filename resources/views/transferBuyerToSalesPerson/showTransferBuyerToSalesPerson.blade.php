<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.TRANSFER_BUYER_TO_SALES_PERSON')
        </h3>
    </div>
    {!! Form::open(array('group' => 'form', 'class' => 'form-horizontal', 'files' => true,'id' => 'transferBuyerForm')) !!}
    {!! Form::hidden('buyer', json_encode($request->buyer)) !!}
    {!! Form::hidden('sales_person_id', $request->sales_person_id) !!}

    {{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="newSalesPersonId">@lang('label.NEW_SALES_PERSON') :</label>
                                <div class="col-md-8">
                                    {!! Form::select('new_sales_person_id', $salesPersonList, null, ['class' => 'form-control js-source-states ','id'=>'newSalesPersonId']) !!}
                                    <span class="text-danger">{{ $errors->first('new_sales_person_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($productArr))
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="bg-blue-hoki bg-font-blue-hoki">
                    <h5 style="padding: 10px;">
                        <strong>
                            @lang('label.NEW_SALES_PERSON_NEED_TO_BE_RELATED_TO_THESE_PRODUCTS_AND_BRANDS')
                        </strong>
                    </h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover relation-view-2">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.PRODUCT_NAME')</th>
                                <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                                <th class=" text-center vcenter" colspan="2">@lang('label.BRAND')</th>
                            </tr>
                        </thead>
                        <tbody id="exerciseData">

                            @php $sl = 0 @endphp
                            @foreach($productArr as $product)
                            <?php
                            $rowspan = !empty($brandRelatedToBuyer[$product['id']]) ? count($brandRelatedToBuyer[$product['id']]) : 1;
                            ?>

                            <tr>
                                <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">
                                    {!! $product['name'] ?? '' !!}
                                </td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">{!! $product['product_category_name'] ?? '' !!}</td>
                                @if(!empty($brandRelatedToBuyer[$product['id']]))
                                <?php $i = 0; ?>
                                @foreach($brandRelatedToBuyer[$product['id']] as $relatedBrandId)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>

                                <td class="text-center vcenter">
                                    @if(!empty($brandInfo[$relatedBrandId]['logo']) && File::exists('public/uploads/brand/' . $brandInfo[$relatedBrandId]['logo']))
                                    <img class="pictogram-min-space" width="30" height="30" src="{{URL::to('/')}}/public/uploads/brand/{{ $brandInfo[$relatedBrandId]['logo'] }}" alt="{{ $brandInfo[$relatedBrandId]['name'] }}"/>
                                    @else 
                                    <img width="30" height="30" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $brandInfo[$relatedBrandId]['name'] }}"/>
                                    @endif
                                </td>
                                <td class="vcenter">
                                    {!! $brandInfo[$relatedBrandId]['name'] ?? ''!!}
                                </td>
                                <?php
                                if ($i > 0) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif      
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12"> 
                <button class="btn btn-inline green btn-submit" type="button" id='submitTransferBuyer'>
                    <i class="fa fa-check"></i> @lang('label.SUBMIT')
                </button> 
                <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>