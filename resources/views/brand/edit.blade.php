@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.EDIT_BRAND')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, array('group' => 'form', 'url' => '#', 'class' => 'form-horizontal', 'files' => true,'id'=>'brandEditForm') ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="origin">@lang('label.ORIGIN') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('origin', $originArr, null, ['class' => 'form-control js-source-states', 'id' => 'origin']) !!}
                                <span class="text-danger">{{ $errors->first('origin') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('description', null, ['id'=> 'description', 'class' => 'form-control','size' => '30x5']) }}
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                        </div>                
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group last">
                            <label class="control-label col-md-4" for="logo">@lang('label.LOGO') :</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                        @if(!empty($target->logo))
                                        <img src="{{URL::to('/')}}/public/uploads/brand/{{$target->logo}}" alt="{{ $target->name}}"/>
                                        @else
                                        <img src="{{URL::to('/')}}/public/img/no_image.png" alt=""> 
                                        @endif

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                            {!! Form::file('logo',['id'=> 'logo']) !!}
                                        </span>
                                        <span class="help-block text-danger">{!! $errors->first('logo') !!}</span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.HAZARD_IMAGE_FOR_IMAGE_DESCRIPTION')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- START:: certificate information -->
                <div class="row margin-top-20 margin-bottom-15">
                    <div class="col-md-offset-2 col-md-7">
                        <div class="border-styel">
                            <div class="lead-info-style">
                                <p class="lead-info-p">@lang('label.CERTIFICATES')</p>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-md-12 table-responsive">

                                    <table class="table table-borderless  table-hover table-striped">
                                        @if(!empty($certificateArr))
                                        @foreach($certificateArr as $key => $certificate)
                                        {!! Form::hidden('certificate_id', $certificate['id'],['id' => 'certificateId']) !!}
                                        <?php
                                        $checked = '';
                                        $disabled = 'disabled';
                                        if (array_key_exists($certificate['id'], $checkedArr)) {
                                            $checked = 'checked';
                                            $disabled = '';
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-left vcenter" width='60'>
                                                <div class="md-checkbox has-success">
                                                    {!! Form::checkbox('certificate_checked['.$certificate['id'].']', $certificate['id'],null, ['id' => 'certificateCheck_'.$certificate['id'], 'data-id'=> $certificate['id'],'class'=> 'md-check',$checked]) !!}
                                                    <label for="{!! 'certificateCheck_'.$certificate['id'] !!}">
                                                        <span class="inc checkbox-text-center"></span>
                                                        <span class="check mark-caheck checkbox-text-center"></span>
                                                        <span class="box mark-caheck checkbox-text-center"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="vcenter text-center " width='50'><img src="{{URL::to('/')}}/public/img/certificate/{{$certificate['logo']}}" width="40px" height="40px"/></td>
                                            <td class="vcenter">{!! (!empty($certificate['name'])) && (!empty($certificate['logo'])) ? $certificate['name'].' '.__('label.CERTIFICATE') :'' !!}</td>
                                            <td class="vcenter">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <span class="btn green btn-file">
                                                        <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                        <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                        <input type="file"  name="certificate_file[{{$certificate['id']}}]" value="" id="certificateFile_{{$certificate['id']}}" class="certificate-file" {{$disabled}}/>

                                                        <input type="hidden" name="prev_certificate_file[{{$certificate['id']}}]" value="{{!empty($prevCertificateArr[$certificate['id']])?$prevCertificateArr[$certificate['id']]:''}}"/>
                                                    </span>
                                                    <span class="fileinput-filename"></span>&nbsp;
                                                    <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                                </div>
                                                <div class="clearfix">
                                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.TECNICAL_DATA_SHEET_FILE_FORMAT_SIZE')
                                                </div>
                                            </td>
                                            <td  colspan="2" class="vcenter text-center" width='150'>
                                                @if(!empty($prevCertificateArr[$certificate['id']]))
                                                <a href="{{URL::to('public/uploads/brandCertificate/'.$prevCertificateArr[$certificate['id']])}}" class="btn fsc-padding tooltips" data-placement="right" title="@lang('label.CLICK_HERE_TO_VIEW_CERTIFICATE')" target="_blank">
                                                    <img src="{{URL::to('/')}}/public/img/certificate/{{$certificate['logo']}}" width="40px" height="40px">
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="vcenter" colspan="10">@lang('label.NO_CERTIFICATE_FOUND')</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--endof div certificate info-->


            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green btn-submit" type="button">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/brand'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.md-check').click(function () {
            var certificateId = $(this).data('id');
            if ($(this).prop('checked')) {
                $('#certificateFile_' + certificateId).prop('disabled', false);
            } else {
                $('#certificateFile_' + certificateId).prop('disabled', true);
            }

        });
        //Save Brand using Certicate Wise Attachment 
        $(document).on("click", ".btn-submit", function (e) {
            e.preventDefault();
            var formData = new FormData($('#brandEditForm')[0]);

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes,Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('brand.update')}}",
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
                            $('.btn-submit').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            location.replace("{{ route('brand.index')}}");

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
                            $('.btn-submit').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }
            });
        });

    });
</script>
@stop