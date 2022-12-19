
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.SALES_PERSON')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr  class="info">
                                    <th  class="vcenter">@lang('label.SL')</th>
                                    <th  class="vcenter text-center">@lang('label.EMPLOYEE_ID')</th>
                                    <th  class="vcenter text-center">@lang('label.PHOTO')</th>
                                    <th  class="vcenter text-center">@lang('label.DESIGNATION')</th>
                                    <th  class="vcenter">@lang('label.NAME')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$salesPersonInfo->isEmpty())
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($salesPersonInfo as $item)
                                <tr>
                                    <td class="vcenter">{{++$sl}}</td>
                                    <td class="vcenter text-center">{{$item->employee_id}}</td>
                                    <td class="text-center vcenter">
                                        <?php if (!empty($item->photo)) { ?>
                                            <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$item->photo}}" alt="{{ $item->name}}"/>
                                        <?php } else { ?>
                                            <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $item->name}}"/>
                                        <?php } ?>
                                    </td>
                                    <td class="vcenter text-center">{{$item->designation_name}}</td>
                                    <td class="vcenter">{{$item->name}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5"> @lang('label.NO_DATA_FOUND')</td>
                                </tr>                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
