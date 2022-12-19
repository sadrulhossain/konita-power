
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <div class="col-md-7 text-right">
            <h4 class="modal-title">@lang('label.LSD_INFO')</h4>
        </div>
        <div class="col-md-5">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>

        </div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 table-responsive form-actions">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="text-center vcenter">@lang('label.LSD')</th>
                            <th class="text-center vcenter">@lang('label.LC_EXPIRY_DATE')</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($lsdInfoArr))
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($lsdInfoArr as $target)
                        <tr>
                            <td class="text-center vcenter"> {{ ++$sl }}</td>
                            <td class="text-center vcenter"> {{ $target['lsd'] }}</td>
                            <td class="text-center vcenter">{{ $target['lc_expiry_date'] }} </td>
                        
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_LSD_INFORMATION_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>

</div>

