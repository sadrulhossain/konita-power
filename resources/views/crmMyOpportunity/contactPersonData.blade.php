<div class="contact-form-div">
    <div class="row margin-top-10">
        <div class="col-md-12">
            <div class="col-md-12 border-bottom-1-green-seagreen">
                <h5><strong>@lang('label.CONTACT_PERSON_INFORMATION')</strong></h5>
            </div>
            {!! Form::open(array('group' => 'form', 'url' => '', 'id' => 'contactFormData', 'class' => 'form-horizontal','files' => true)) !!}
            {{csrf_field()}}
            {!! Form::hidden('opportunity_id', $target->id) !!}
            <div class="col-md-12 margin-top-10">
                <div class="table-responsive webkit-scrollbar">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="active">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter">@lang('label.NAME')</th>
                                <th class="text-center vcenter">@lang('label.DESIGNATION')</th>
                                <th class="text-center vcenter">@lang('label.EMAIL')</th>
                                <th class="text-center vcenter">@lang('label.PHONE')</th>
                                <th class="text-center vcenter">@lang('label.PRIMARY_CONTACT')</th>
                                <th class="text-center vcenter"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $v3 = 'c' . uniqid();
                            ?>
                            @if(!empty($contactArr))
                            <?php
                            $cCounter = 0;
                            $cSl = 0;
                            ?>
                            @foreach($contactArr as $cKey => $cInfo)
                            <tr>
                                <td class="text-center vcenter initial-contact-sl width-50">{!! ++$cSl !!}</td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$cKey.'][name]', $cInfo['name'] ?? null, ['id'=> 'contactName_'.$cKey, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$cKey.'][designation]', $cInfo['designation'] ?? null, ['id'=> 'contactDesignation_'.$cKey, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$cKey.'][email]', $cInfo['email'] ?? null, ['id'=> 'contactEmail_'.$cKey, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$cKey.'][phone]', $cInfo['phone'] ?? null, ['id'=> 'contactPhone_'.$cKey, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter width-50">
                                    <div class="checkbox-center md-checkbox has-success width-inherit">
                                        {!! Form::checkbox('contact['.$cKey.'][primary]', 1, $cInfo['primary'] ?? null, ['id' => 'contactPrimary_'.$cKey, 'data-key' => $cKey, 'class'=> 'md-check primary-contact']) !!}
                                        <label for="contactPrimary_{{$cKey}}">
                                            <span class="inc"></span>
                                            <span class="check box-double-rounded mark-caheck"></span>
                                            <span class="box box-rounded mark-caheck"></span>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center vcenter width-50">
                                    @if($cCounter == 0)
                                    <button class="btn btn-inline green-haze add-new-contact-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_CONTACT_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-inline btn-danger remove-contact-row tooltips" title="Remove" type="button">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            <?php $cCounter++; ?>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center vcenter initial-contact-sl width-50">1</td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$v3.'][name]', null, ['id'=> 'contactName_'.$v3, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$v3.'][designation]', null, ['id'=> 'contactDesignation_'.$v3, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$v3.'][email]', null, ['id'=> 'contactEmail_'.$v3, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter">
                                    {!! Form::text('contact['.$v3.'][phone]', null, ['id'=> 'contactPhone_'.$v3, 'class' => 'form-control']) !!}
                                </td>
                                <td class="text-center vcenter width-50">
                                    <div class="checkbox-center md-checkbox has-success width-inherit">
                                        {!! Form::checkbox('contact['.$v3.'][primary]',1,null, ['id' => 'contactPrimary_'.$v3, 'data-key' => $v3, 'class'=> 'md-check primary-contact']) !!}
                                        <label for="contactPrimary_{{$v3}}">
                                            <span class="inc"></span>
                                            <span class="check box-double-rounded mark-caheck"></span>
                                            <span class="box box-rounded mark-caheck"></span>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center vcenter width-50">
                                    <button class="btn btn-inline green-haze add-new-contact-row tooltips" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_CONTACT_INFO')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        <tbody id="newContactTbody"></tbody>
                    </table>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-md-6 pull-right">
            <button type="button" class="btn green margin-bottom-10"  id="saveContactData" data-opportunity-id="{{ $target->id}}">
                <i class="fa fa-check"></i> @lang('label.SAVE')
            </button>
            <button type="button" data-placement="left" class="btn dark btn-inline tooltips margin-bottom-10 close-btn" title="@lang('label.CLOSE')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    function rearrangeSL(type) {
        var sl = 0;
        $('.initial-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
        $('.new-' + type + '-sl').each(function () {
            sl = sl + 1;
            $(this).text(sl);
        });
    }
</script>