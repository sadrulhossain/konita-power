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