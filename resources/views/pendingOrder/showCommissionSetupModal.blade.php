<div class="modal-dialog modal-lg" id="exampleModal">
    <div class="modal-content" >
        <div class="modal-header clone-modal-header" >
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
            <h3 class="modal-title text-center">
                @lang('label.COMMISSION_SETUP')
            </h3>
        </div>
        <div class="modal-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal' ,'id' => 'cmsnSubmitForm')) !!}
            {!! Form::hidden('inquiry_id', $target->id) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="showCommissionSetupEdit">
                        <!-- Begin Filter-->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th class="vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.PRODUCT')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.BRAND')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.GRADE')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.GSM')</th>
                                        <th class="vcenter text-center" colspan="2">@lang('label.KONITA_CMSN')</th>
                                        <th class="vcenter text-center" colspan="2">@lang('label.PRINCIPLE_COMMISSION')</th>
                                        <th class="vcenter text-center" colspan="2">@lang('label.SALES_PERSON_COMMISSION')</th>
                                        <th class="vcenter text-center" colspan="2">@lang('label.BUYER_COMMISSION')</th>
                                        <th class="vcenter text-center" colspan="2">@lang('label.REBATE_COMMISSION')</th>
                                    </tr>
                                    <tr>
                                        <th class="vcenter">@lang('label.COMMISSION')</th>
                                        <th class="vcenter">@lang('label.REMARKS')</th>
                                        <th class="vcenter">@lang('label.COMMISSION')</th>
                                        <th class="vcenter">@lang('label.REMARKS')</th>
                                        <th class="vcenter">@lang('label.COMMISSION')</th>
                                        <th class="vcenter">@lang('label.REMARKS')</th>
                                        <th class="vcenter">@lang('label.COMMISSION')</th>
                                        <th class="vcenter">@lang('label.REMARKS')</th>
                                        <th class="vcenter">@lang('label.COMMISSION')</th>
                                        <th class="vcenter">@lang('label.REMARKS')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$inquiryDetails->isEmpty())
                                    <?php
                                    $i = 0;
                                    $readonly = array_key_exists($comsnInquiryId, $inquiryIdArr) ? 'readonly' : '';
                                    ?>
                                    @foreach($inquiryDetails as $item)
                                    <tr>
                                        <td class="vcenter text-center">{{ ++$i }}</td>
                                        <td class="vcenter">
                                            {{!empty($item->product_name)?$item->product_name:''}}
                                        </td>
                                        <td class="vcenter">{{!empty($item->brand_name)?$item->brand_name:''}}</td>
                                        <td class="vcenter">{{!empty($item->grade_name)?$item->grade_name:''}}</td>
                                        <td class="vcenter">{{!empty($item->gsm)?$item->gsm:''}}</td>
                                        <td class="vcenter text-right width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][konita_cmsn]', (!empty($prevComsn[$item->inquiry_details_id]['konita_cmsn']) ? $prevComsn[$item->inquiry_details_id]['konita_cmsn'] : (!empty($prevComsn[0]['konita_cmsn']) ? $prevComsn[0]['konita_cmsn'] : null)), ['id'=> '', 'class' => 'form-control text-right integer-decimal-only konitaCmsn','autocomplete' => 'off',$readonly]) !!} 
                                            </div>
                                        </td>
                                        <td class="vcenter width-200">
                                            {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][konita_remarks]', (!empty($prevComsn[$item->inquiry_details_id]['konita_remarks']) ? $prevComsn[$item->inquiry_details_id]['konita_remarks'] : (!empty($prevComsn[0]['konita_remarks']) ? $prevComsn[0]['konita_remarks'] : null)), ['id'=> 'konitaRemarks', 'class' => 'form-control width-inherit', 'placeholder' => 'Remarks...',$readonly]) !!}
                                        </td>
                                        <td class="vcenter text-right width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][principle_cmsn]', (!empty($prevComsn[$item->inquiry_details_id]['principle_cmsn']) ? $prevComsn[$item->inquiry_details_id]['principle_cmsn'] : (!empty($prevComsn[0]['principle_cmsn']) ? $prevComsn[0]['principle_cmsn'] : null)), ['id'=> 'principleCmsn', 'class' => 'form-control text-right integer-decimal-only','autocomplete' => 'off',$readonly]) !!}
                                            </div>
                                        </td>
                                        <td class="vcenter width-200">
                                            {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][principle_remarks]', (!empty($prevComsn[$item->inquiry_details_id]['principle_remarks']) ? $prevComsn[$item->inquiry_details_id]['principle_remarks'] : (!empty($prevComsn[0]['principle_remarks']) ? $prevComsn[0]['principle_remarks'] : null)), ['id'=> 'principleRemarks', 'class' => 'form-control width-inherit', 'placeholder' => 'Remarks...',$readonly]) !!}
                                        </td>
                                        <td class="vcenter text-right width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][sales_person_cmsn]', (!empty($prevComsn[$item->inquiry_details_id]['sales_person_cmsn']) ? $prevComsn[$item->inquiry_details_id]['sales_person_cmsn'] : (!empty($prevComsn[0]['sales_person_cmsn']) ? $prevComsn[0]['sales_person_cmsn'] : null)), ['id'=> '', 'class' => 'form-control text-right integer-decimal-only salesPersonCmsn','autocomplete' => 'off',$readonly]) !!} 
                                            </div>
                                        </td>
                                        <td class="vcenter width-200">
                                            {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][sales_person_remarks]', (!empty($prevComsn[$item->inquiry_details_id]['sales_person_remarks']) ? $prevComsn[$item->inquiry_details_id]['sales_person_remarks'] : (!empty($prevComsn[0]['sales_person_remarks']) ? $prevComsn[0]['sales_person_remarks'] : null)), ['id'=> 'salesPersonRemarks', 'class' => 'form-control width-inherit', 'placeholder' => 'Remarks...',$readonly]) !!}
                                        </td>
                                        <td class="vcenter text-right width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][buyer_cmsn]', (!empty($prevComsn[$item->inquiry_details_id]['buyer_cmsn']) ? $prevComsn[$item->inquiry_details_id]['buyer_cmsn'] : (!empty($prevComsn[0]['buyer_cmsn']) ? $prevComsn[0]['buyer_cmsn'] : null)), ['id'=> '', 'class' => 'form-control text-right integer-decimal-only buyerCmsn','autocomplete' => 'off',$readonly]) !!} 
                                            </div>
                                        </td>
                                        <td class="vcenter width-200">
                                            {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][buyer_remarks]', (!empty($prevComsn[$item->inquiry_details_id]['buyer_remarks']) ? $prevComsn[$item->inquiry_details_id]['buyer_remarks'] : (!empty($prevComsn[0]['buyer_remarks']) ? $prevComsn[0]['buyer_remarks'] : null)), ['id'=> 'buyerRemarks', 'class' => 'form-control width-inherit', 'placeholder' => 'Remarks...',$readonly]) !!}
                                        </td>
                                        <td class="vcenter text-right width-150">
                                            <div class="input-group bootstrap-touchspin width-inherit">
                                                <span class="input-group-addon bootstrap-touchspin-prefix bold">$</span>
                                                {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][rebate_cmsn]', (!empty($prevComsn[$item->inquiry_details_id]['rebate_cmsn']) ? $prevComsn[$item->inquiry_details_id]['rebate_cmsn'] : (!empty($prevComsn[0]['rebate_cmsn']) ? $prevComsn[0]['rebate_cmsn'] : null)), ['id'=> '', 'class' => 'form-control text-right integer-decimal-only rebateCmsn','autocomplete' => 'off',$readonly]) !!} 
                                            </div>
                                        </td>
                                        <td class="vcenter width-200">
                                            {!! Form::text('comsn_setup['.$item->inquiry_details_id.'][rebate_remarks]', (!empty($prevComsn[$item->inquiry_details_id]['rebate_remarks']) ? $prevComsn[$item->inquiry_details_id]['rebate_remarks'] : (!empty($prevComsn[0]['rebate_remarks']) ? $prevComsn[0]['rebate_remarks'] : null)), ['id'=> 'rebateRemarks', 'class' => 'form-control width-inherit', 'placeholder' => 'Remarks...',$readonly]) !!}
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
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-9 text-left" style="border-right: 2px solid #2f353b;">

                    </div>
                    <div class="col-md-3 text-right">
                        @if(!array_key_exists($comsnInquiryId, $inquiryIdArr))
                        <button type="button" class="btn green"  id="cmsnSaveBtn">
                            <i class="fa fa-check"></i> @lang('label.SAVE')
                        </button>
                        @endif
                        <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-inline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>

