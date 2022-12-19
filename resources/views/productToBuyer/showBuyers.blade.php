
<span class="label label-success" >@lang('label.TOTAL_NUM_OF_BUYERS'): {!! !$buyerArr->isEmpty()?count($buyerArr):0 !!}</span>
<span class="label label-danger" >@lang('label.BUYERS_RELATED_TO_THIS_PRODUCT'): {!! !empty($buyerRelateToProduct[$request->product_id])?count($buyerRelateToProduct[$request->product_id]):0 !!}</span>
@if(!empty($userAccessArr[17][5]))
<button class="label label-primary tooltips" href="#modalRelatedBuyer" id="relateBuyer"  data-toggle="modal" title="@lang('label.SHOW_RELATED_BUYERS')">@lang('label.SHOW_RELATED_BUYERS')</button>
@endif
<table class="table table-bordered table-hover" id="dataTable">
    <thead>
        <tr>
            <th class="text-center vcenter">
                <div class="md-checkbox has-success">
                    {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
                    <label for="checkAll">
                        <span class="inc"></span>
                        <span class="check mark-caheck"></span>
                        <span class="box mark-caheck"></span>
                    </label>
                </div>
            </th>
            <th class="vcenter">@lang('label.BUYER_NAME')</th>
            <th class="vcenter">@lang('label.RELATED_PRODUCT_S')</th>
        </tr>
    </thead>
    <tbody>
        @if(!$buyerArr->isEmpty())
        @foreach($buyerArr as $buyer)
        <?php
        //check and show previous value
        $checked = '';
        if (!empty($buyerRelateToProduct[$request->product_id]) && array_key_exists($buyer->id, $buyerRelateToProduct[$request->product_id])) {
            $checked = 'checked';
        }
        ?>
        <tr>
            <td class="text-center vcenter">
                <div class="md-checkbox has-success">
                    {!! Form::checkbox('buyer['.$buyer->id.']', $buyer->id, $checked, ['id' => $buyer->id, 'data-id'=> $buyer['id'],'class'=> 'md-check bf-check']) !!}
                    <label for="{!! $buyer->id !!}">
                        <span class="inc"></span>
                        <span class="check mark-caheck"></span>
                        <span class="box mark-caheck"></span>
                    </label>
                </div>
            </td>
            <td class="vcenter">{!! $buyer->name !!}</td>
            <td class="vcenter">
                @if(!empty($relateProductList[$buyer->id]))
                <ol>
                    @foreach($relateProductList[$buyer->id] as $relateProduct)
                    <li>{!! $relateProduct !!}</li>
                    @endforeach
                </ol>
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td class="vcenter" colspan="4">@lang('label.NO_BUYER_FOUND')</td>
        </tr>
        @endif
    </tbody>
</table>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-4 col-md-8">
            @if(!empty($userAccessArr[17][7]))
            <button class="btn btn-circle green btn-submit" id="saveProductToBuyerRel" type="button">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @endif
            @if(!empty($userAccessArr[17][1]))
            <a href="{{ URL::to('/productToBuyer') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
            @endif
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.tooltips').tooltip();
        
        $('#checkAll').on("click", function () {  //'check all' change
            if ($(this).prop('checked')) {
                $('.bf-check').prop("checked", true);
            }
            else{
                $('.bf-check').prop("checked", false);
            }
        });

        //For Product Check and relate with seales person
        $('.bf-check').change(function () {
            if (this.checked == false) { //if this item is unchecked
                $('#checkAll')[0].checked = false; //change 'check all' checked status to false
                var productId = $(this).data('id');
            }

            //check 'check all' if all checkbox items are checked
            if ($('.bf-check:checked').length == $('.bf-check').length) {
                $('#checkAll')[0].checked = true; //change 'check all' checked status to true
            }

        });
        
        //For DataTable Search
        @if(!$buyerArr->isEmpty())
        $('#dataTable').dataTable({
            "paging": false,
            "info": false,
                        "language": {
                "search": "Search Keywords : ",
            },
            "attr": {
                "class": "form-control"
            }
        });
        @endif

    });

    
</script>