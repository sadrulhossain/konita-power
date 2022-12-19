@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-commenting"></i>@lang('label.BUYER_MESSAGE')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'buyerMessage/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="inquiryId">@lang('label.ORDER_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('inquiry_id',  $orderNoList, Request::get('inquiry_id'), ['class' => 'form-control js-source-states','id'=>'inquiryId']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="messageType">@lang('label.MESSAGE_TYPE')</label>
                        <div class="col-md-8">
                            {!! Form::select('message_type',  $messageTypeList, Request::get('message_type'), ['class' => 'form-control js-source-states','id'=>'messageType']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="row crm-status-summary">
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-hover table-head-fixer-color-grey-mint">
                            <thead>
                                <tr>
                                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.BUYER')</th>
                                    <th class="vcenter">@lang('label.MESSAGE')</th>
                                    <th class="vcenter text-center">@lang('label.ORDER_BASED')</th>
                                    <th class="vcenter">@lang('label.ORDER_NO')</th>
                                    <th class="vcenter">@lang('label.SENT_BY')</th>
                                    <th class="vcenter text-center">@lang('label.SENT_AT')</th>
                                    <th class="vcenter text-center">@lang('label.READ_STATUS')</th>
                                    <th class="td-actions vcenter text-center">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($buyerMessageArr))
                                @foreach($buyerMessageArr as $key => $info)
                                <tr>
                                    <td class="text-center vcenter">{!! $key !!}</td>
                                    <td class="vcenter">{!! $info['buyer'] ?? '' !!}</td>
                                    <td class="vcenter">
                                        {!! !empty($info['message']) ? Helper::trimString($info['message']) : '' !!}
                                    </td>
                                    <td class="text-center vcenter">
                                        @if($info['inquiry_id'] != 0)
                                        <span class="label label-sm label-green-soft">@lang('label.YES')</span>
                                        @else
                                        <span class="label label-sm label-gray-mint">@lang('label.NO')</span>
                                        @endif
                                    </td>
                                    <td class="vcenter">{!! $info['order_no'] ?? '' !!}</td>
                                    <td class="vcenter">
                                        {!! !empty($info['updated_by']) && !empty($userArr[$info['updated_by']]['full_name']) ? $userArr[$info['updated_by']]['full_name'] : '' !!}
                                    </td>
                                    <td class="text-center vcenter">
                                        {!! !empty($info['updated_at']) ? Helper::formatDateTime($info['updated_at']) : '' !!}
                                    </td>
                                    <td class="text-center vcenter read-{{$info['inquiry_id']}}-{{$info['buyer_id']}}">
                                        <?php $newKey = $info['buyer_id'] . '_' . $info['inquiry_id']; ?>
                                        @if(!empty($hasUnreadMsg) && in_array($newKey, $hasUnreadMsg))
                                        <i class="read tooltips fa fa-comment text-blue-madison" title="@lang('label.UNREAD')"></i>
                                        @else
                                        <i class="read tooltips fa fa-comment-o text-blue-madison" title="@lang('label.READ')"></i>
                                        @endif
                                    </td>

                                    <td class="td-actions text-center vcenter">
                                        <div class="width-inherit">
                                            <button class="btn btn-xs purple-sharp tooltips vcenter order-messaging" title="@lang('label.VIEW_MESSAGES')" 
                                                    href="#modalOrderMessaging" data-id="{!! $info['inquiry_id'] ?? 0 !!}" 
                                                    data-buyer-id="{!! $info['buyer_id'] !!}" data-toggle="modal">
                                                <i class="fa fa-commenting"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class="vcenter" colspan="9">@lang('label.NO_MESSAGE_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>	
    </div>
</div>


<!-- Modal start -->

<!--order messaging-->
<div class="modal fade" id="modalOrderMessaging" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showOrderMessaging"></div>
    </div>
</div>

<!-- Modal end-->


<script type="text/javascript">
    $(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        //order messaging modal
        $(document).on("click", '.order-messaging', function () {
            var inquiryId = $(this).attr("data-id");
            var buyerId = $(this).attr("data-buyer-id");
            var countTotal = $('span.badge-user-total-message').text();
            var countCommon = $('span.badge-user-common-message').text();
            var countOrder = $('span.badge-user-order-message').text();

            $.ajax({
                url: "{{ URL::to('/buyerMessage/getOrderMessaging')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inquiry_id: inquiryId,
                    buyer_id: buyerId,
                },
                beforeSend: function () {
                    $("#showOrderMessaging").html('');
                },
                success: function (res) {
                    if (typeof countTotal != 'undefined') {
                        countTotal = countTotal - 1;
                        var s = countTotal > 1 ? 's' : '';
                        if (countTotal == 0) {
                            $('span.badge-user-total-message').remove();
                        } else {
                            $('span.badge-user-total-message').text(countTotal);
                            $('h3.h3-user-total-message').text("You Have "+ countTotal + "Unread Message" + s);
                        }
                        
                        if (inquiryId == 0) {
                            if (countCommon > 0) {
                                countCommon = countCommon - 1;
                                $('span.badge-user-common-message').text(countCommon);
                            }
                        } else {
                            if (countOrder > 0) {
                                countOrder = countOrder - 1;
                                $('span.badge-user-order-message').text(countOrder);
                            }
                        }
                    }
                    
                    $(".read-" + inquiryId + "-" + buyerId + " i.read").removeClass("fa-comment").addClass("fa-comment-o");
                    $(".read-" + inquiryId + "-" + buyerId + " i.read").attr('data-original-title', "@lang('label.READ')");

                    
                    $("#showOrderMessaging").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

        //Send Message
        $(document).on("click", ".send-message", function (e) {
//            e.preventDefault();
            var formData = new FormData($('#setMessageFrom')[0]);

            $.ajax({
                url: "{{ URL::to('buyerMessage/setMessage')}}",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('.send-message').prop('disabled', true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('.send-message').prop('disabled', false);
                    $('#message').val('');
                    $('.message-body').html(res.messageBody);
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    $('.send-message').prop('disabled', false);
                    App.unblockUI();
                }
            });
        });


    });

</script>

@stop