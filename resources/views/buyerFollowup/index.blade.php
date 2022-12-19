@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.BUYER_FOLLOWUP')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'buyerFollowup/filter','class' => 'form-horizontal')) !!}
                    @csrf
                    <div class="col-md-offset-2 col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER') </label>
                            <div class="col-md-8">
                                {!! Form::select('buyer_id',  $buyerList, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
                                <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md green btn-inline filter-submit margin-bottom-20">
                                <i class="fa fa-check"></i> @lang('label.GENERATE')
                            </button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <!-- End Filter -->
                </div>
            </div>
            @if($request->generate == 'true')
            <div class="row margin-top-20 buyer-followup">
                @if(!empty($userAccessArr[66][2]))
                <div class="col-md-12 text-right margin-bottom-20">
                    <button class="btn green-seagreen btn-sm add-followup" href="#modalSetFollowup" data-id="{!! $request->buyer_id !!}" data-toggle="modal" data-placement="top" data-rel="tooltip">
                        <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD_FOLLOWUP')
                    </button>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="table-responsive max-height-500 padding-top-10 webkit-scrollbar">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <div class="portlet-body">
                                @if(!empty($finalArr))
                                <div class="mt-timeline-2">
                                    <div class="mt-timeline-line border-grey-steel"></div>
                                    <ul class="mt-container">
                                        @foreach($finalArr as $followUpDate=>$infoArr)
                                        <?php
                                        $i = 0;
                                        ?>
                                        @foreach($infoArr as $history)

                                        <li class="mt-item">
                                            <?php
                                            $bgColor = $iconShape = $labelColor = $finalColor = $bgFont = '';
                                            if ($history['status'] == '1') {
                                                $bgColor = 'bg-yellow bg-font-yellow';
                                                $bgFont = 'font-yellow';
                                                $iconShape = 'fa fa-user';
                                                $labelColor = 'label-yellow';
                                                $ribbonColor = 'ribbon-color-yellow';
                                            } else if ($history['status'] == '2') {
                                                $bgColor = 'bg-green-seagreen bg-font-green-seagreen';
                                                $bgFont = 'font-green-seagreen';
                                                $iconShape = 'fa fa-smile-o';
                                                $labelColor = 'label-green-seagreen';
                                                $ribbonColor = 'ribbon-color-green-seagreen';
                                            } else if ($history['status'] == '3') {
                                                $bgColor = 'bg-red-soft bg-font-red-soft';
                                                $bgFont = 'font-red-soft';
                                                $iconShape = 'fa fa-frown-o';
                                                $labelColor = 'label-red-soft';
                                                $ribbonColor = 'ribbon-color-red-soft';
                                            } else {
                                                $labelColor = 'label-purple-sharp';
                                                $ribbonColor = 'ribbon-color-purple-sharp';
                                            }
                                            ?>
                                            <div class="mt-timeline-icon border-grey-steel {{$bgColor}}">
                                                <i class="{{$iconShape}} bar-icon"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container track-history">
                                                    <div class="portlet mt-element-ribbon light portlet-fit portlet-box-background bordered margin-bottom-0">
                                                        @if(!empty($history['updated_by']))
                                                        <?php
                                                        $updatedBy = $history['updated_by'];
                                                        $col1 = '3';
                                                        $col2 = '9';
                                                        if (!empty($history['updated_at'])) {
                                                            $col1 = '2';
                                                            $col2 = '10';
                                                        }
                                                        ?>
                                                        @if(!empty($userArr))
                                                        @if(array_key_exists($updatedBy, $userArr))
                                                        <?php $user = $userArr[$updatedBy]; ?>
                                                        <div class="portlet-title portlet-title-border">
                                                            <div class="caption">
                                                                <div class="row">
                                                                    <div class="col-md-{{$col1}}">
                                                                        @if(!empty($user['photo']) && File::exists('public/uploads/user/'.$user['photo']))
                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/uploads/user/{{$user['photo']}}" alt="{{ $user['full_name'] }}"/>
                                                                        @else
                                                                        <img width="40" height="40" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $user['full_name'] }}"/>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-{{$col2}}">
                                                                        <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $user['full_name'] !!}</span><br/>
                                                                        <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! __('label.EMPLOYEE_ID').' : '.$user['employee_id'] !!}</span>
                                                                        @if(!empty($history['updated_at']))
                                                                        <br/><i class="fa fa-clock-o {{ $bgFont }}"> </i><span class="caption-subject {{ $bgFont }} bold font-size-14">{!! Helper::formatDateTime($history['updated_at']) !!}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endif
                                                        @endif
                                                        <div class="portlet-title portlet-no-css">
                                                            <div class="caption">
                                                                <i class=" icon-calendar {{ $bgFont }}"></i>
                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">{!! $history['follow_up_date'] !!}</span>
                                                            </div>
                                                        </div>
                                                        @if(!empty($history['updated_by']))
                                                        @if(!empty($userArr))
                                                        @if(array_key_exists($updatedBy, $userArr))
                                                        <div class="ribbon ribbon-right buyer-followup-status-ribbon ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']]) && $history['status'] != '0') ? $statusList[$history['status']] : __('label.N_A') !!}
                                                        </div>
                                                        @endif
                                                        @endif
                                                        @endif
                                                        @if(!empty($history['order_no']))
                                                        <div class="portlet-title portlet-no-css">
                                                            <div class="caption">
                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.ORDER_NO')</span>
                                                                &nbsp;<span class="caption-subject bold font-size-14">{!! $history['order_no'] !!}</span>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="portlet-title portlet-no-css">
                                                            <div class="caption">
                                                                <span class="caption-subject {{ $bgFont }} bold font-size-14">@lang('label.REMARKS')</span>
                                                            </div>
                                                        </div>
                                                        @if(empty($history['updated_by']))
                                                        <div class="ribbon ribbon-right ribbon-shadow ribbon-round ribbon-border-dash-hor {{$ribbonColor}}">
                                                            <div class="ribbon-sub ribbon-clip ribbon-right ribbon-round"></div>
                                                            <i class="{{$iconShape}}"></i>&nbsp;{!! (isset($history['status']) && isset($statusList[$history['status']]) && $history['status'] != '0') ? $statusList[$history['status']] : __('label.N_A') !!} 
                                                        </div>
                                                        @endif
                                                        <div class="portlet-body portlet-body-padding">
                                                            <p class="track-text font-size-14">
                                                                {!! $history['remarks'] !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <?php
                                        $i++;
                                        ?>
                                        @endforeach
                                        @endforeach
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-grey-mint bg-font-grey-mint">
                                                <i class="icon-arrow-up"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                @else
                                <div class="col-md-12 text-center">
                                    <div class="alert alert-danger">
                                        <p>
                                            <i class="fa fa-warning"></i>
                                            @lang('label.FOLLOW_UP_HISTORY_IS_NOT_AVAILABLE')
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>	
    </div>
</div>

<!-- Modal start -->
<div class="modal fade" id="modalSetFollowup" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showSetFollowup">
        </div>
    </div>
</div>
<!-- Modal end-->

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("change", "#buyerId", function () {
            $(".buyer-followup").html('');
            $(".buyer-followup").hide();
        });

        //add followup modal
        $(document).on("click", ".add-followup", function (e) {
            var buyerId = $(this).attr('data-id');

            $.ajax({
                url: "{{ URL::to('buyerFollowup/getAddFollowup')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    buyer_id: buyerId,
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $("#showSetFollowup").html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2({dropdownParent: $('#showSetFollowup'),width: '100%'});
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });

        //set payment form function
        $(document).on("click", "#saveFollowupHistory", function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "You can not undo this action!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var options = {
                        closeButton: true,
                        debug: false,
                        positionClass: "toast-bottom-right",
                        onclick: null,
                    };

                    // Serialize the form data
                    var form_data = new FormData($('#saveFollowupForm')[0]);
                    $.ajax({
                        url: "{{URL::to('buyerFollowup/setAddFollowup')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                            $('#saveFollowupHistory').prop('disabled', true);
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            // similar behavior as an HTTP redirect
                            setTimeout(location.reload(), 1000);
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
                            $('#saveFollowupHistory').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });
        //endof set payment form

    });
</script>    
@stop