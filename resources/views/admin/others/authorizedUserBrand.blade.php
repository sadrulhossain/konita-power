
<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.BRAND_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr class="center info">
                                    <th>@lang('label.SL_NO')</th>
                                    <th>@lang('label.LOGO')</th>
                                    <th>@lang('label.NAME')</th>
                                    <th class="vcenter">@lang('label.ORIGIN')</th>
                                    <th width="40%">@lang('label.DESCRIPTION')</th>
                                    <th class="text-center vcenter">@lang('label.CERTIFICATE')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($brandDataArr))
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($brandDataArr as $target)
                                <tr>
                                    <td class="vcenter">{{ ++$sl }}</td>
                                    <td class="text-center vcenter">
                                        @if(!empty($target['logo']))
                                        <img class="pictogram-min-space tooltips" width="50" height="50" src="{{URL::to('/')}}/public/uploads/brand/{{ $target['logo'] }}" alt="{{ $target['name']}}" title="{{ $target['name'] }}"/>
                                        @else 
                                        <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                        @endif
                                    </td>
                                    <td class="vcenter">{{ $target['name'] }}</td>
                                    <td class="vcenter">{!! $target['origin'] !!}</td>
                                    <td class="vcenter">{{ $target['description'] }}</td>

                                    <td class="text-center vcenter">
                                        <div width="100%">
                                            @if(!empty($brandCertificateArr[$target['id']]))
                                            @foreach($brandCertificateArr[$target['id']] as $key => $item)
                                            <label>&nbsp;</label>
                                            <a href="{{URL::to('public/uploads/brandCertificate/'.$item)}}" class="btn fsc-padding tooltips" title="@lang('label.CLICK_HERE_TO_VIEW_CERTIFICATE')" target="_blank">
                                                <i class="" aria-hidden="true"><img src="{{URL::to('/')}}/public/img/certificate/{{$certificateArr[$key]}}" width="20px" height="20px"></i>
                                            </a>
                                            @endforeach
                                            @else
                                            <span class="label label-warning purple-stripe">
                                                @lang('label.N/A')
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6"> @lang('label.NO_DATA_FOUND')</td>
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


