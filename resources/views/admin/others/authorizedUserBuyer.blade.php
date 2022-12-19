<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h3 class="modal-title text-center">
            @lang('label.BUYER_LIST')
        </h3>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row">
                <!-- Begin Filter-->
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label margin-top-8 col-md-2" for="search">@lang('label.BUYER')</label>
                        <div class="col-md-6">
                            {!! Form::text('search',  null, ['class' => 'form-control tooltips search', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                            <datalist id="search">
                                @if(!empty($nameArr))
                                @foreach($nameArr as $name)
                                <option value="{{$name->name}}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-md green-seagreen btn-outline name-search margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.SEARCH')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <button type="button" class="btn btn-md purple-soft btn-outline all-search margin-bottom-20">
                            @lang('label.SEARCH_ALL_BUYERS')
                        </button>
                    </div>
                </div>
                <!-- End Filter -->

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-head-fixer-color">
                            <thead>
                                <tr  class="info">
                                    <th  class="vcenter" rowspan="2">@lang('label.SL')</th>
                                    <th  class="vcenter" rowspan="2">@lang('label.BUYER_NAME')</th>
                                    <th  class="vcenter" rowspan="2">@lang('label.COUNTRY')</th>
                                    <th class="vcenter text-center" colspan="2">@lang('label.CONTACT_PERSON')</th>
                                    <th  class="vcenter" rowspan="2">@lang('label.RELATED_SALES_PERSON_LIST')</th>
                                </tr>
                                <tr class="active">
                                    <th class="vcenter">@lang('label.NAME')</th>
                                    <th class="vcenter">@lang('label.PHONE')</th>
                                </tr>
                            </thead>
                            <tbody id="showBuyer">
                                @if(!$buyerInfo->isEmpty())
                                <?php
                                $sl = 0;
                                ?>
                                @foreach($buyerInfo as $item)
                                <tr>
                                    <td class="vcenter">{{++$sl}}</td>
                                    <td class="vcenter">{{$item->name}}</td>
                                    <td class="vcenter">{{$item->country_name}}</td>
                                    <td class="vcenter">{!! $contactArr[$item->id]['name'] ?? '' !!}</td>


                                    @if(is_array($contactArr[$item->id]['phone']))
                                    <td class="vcenter">
                                        <?php
                                        $lastValue = end($contactArr[$item->id]['phone']);
                                        ?>
                                        @foreach($contactArr[$item->id]['phone'] as $key => $contact)
                                        {{$contact}}
                                        @if($lastValue !=$contact)
                                        <span>,</span>
                                        @endif
                                        @endforeach
                                    </td>
                                    @else
                                    <td class="vcenter">{!! $contactArr[$item->id]['phone'] ?? '' !!}</td>
                                    @endif
                                    <td class="vcenter">
                                        @if(!empty($relatedSalesPersonArr[$item->id]))
                                        <?php $sspl = 0; ?>
                                        @foreach($relatedSalesPersonArr[$item->id] as $id => $name)
                                        {!! ++$sspl. '. '.$name !!}
                                        @if(array_key_exists($id, $activeSalesPersonArr[$item->id]))
                                        <button type="button" class="btn btn-xs padding-5 cursor-default  btn-circle green-seagreen tooltips" title="{{ __('label.ACTIVE') }}">

                                        </button><br/>
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7"> @lang('label.NO_DATA_FOUND')</td>
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
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {


});
</script>