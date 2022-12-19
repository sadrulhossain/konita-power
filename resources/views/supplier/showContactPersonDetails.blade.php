<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            {{ $supplierName}}@lang('label.CONTACT_PERSON_DETAILS')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="">
                                    <th class="vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter"> {{__('label.PHOTO')}} </th>
                                    <th class="vcenter"> {{__('label.NAME')}} </th>
                                    <th class="vcenter"> {{__('label.EMAIL')}} </th>
                                    <th class="vcenter"> {{__('label.PHONE')}} </th>
                                    <th class="vcenter"> {{__('label.FIRST_INTRODUCTION_DATE')}} </th>
                                    <th class="vcenter"> {{__('label.CONTACT_NOTE')}} </th>
                                </tr>
                            </thead>
                            @if(!empty($contactPersonArr))
                            <?php $serial = 0; ?> 
                            @foreach($contactPersonArr as $personData)

                            <tr>
                                <td class="text-center vcenter">{!! ++$serial !!}</td>
                                <td class="text-center vcenter">
                                    @if(!empty($personData['photo']))
                                    <img src="{{URL::to('/')}}/public/uploads/supplier/contact_person/{{$personData['photo']}}" 
                                         alt="{{ $personData['name']}}" width="50" height="50"/>
                                    @else
                                    <img src="{{URL::to('/')}}/public/img/unknown.png" alt="" width="50" height="50"> 
                                    @endif
                                </td>
                                <td class="vcenter">{!! $personData['name'] !!}</td>
                                <td class="vcenter">{!! $personData['email'] !!}</td>
                                <td class="vcenter">{!! $personData['phone'] !!}</td>
                                <td class="vcenter">{!! isset($personData['introduction_date']) ? Helper::formatDate($personData['introduction_date']) : '' !!}</td>
                                <td class="vcenter">{!! $personData['note'] !!}</td>

                            </tr>
                            @endforeach 
                            @else
                            <tr>
                                <td colspan="9">{{trans('label.NO_CONTACT_PERSON_FOUND_FOR_THIS_SUPPLIER')}}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>
<!-- END:: Contact Person Information-->