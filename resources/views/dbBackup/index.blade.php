@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i>{{__('label.DB_BACKUP')}} 
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('group' => 'form', 'url' => 'dbBackup/filter','class' => 'form-horizontal')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fromDate">{{__('label.FROM_DATE')}} :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="toDate">{{__('label.TO_DATE')}} :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker2">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'DD MM YYYY', 'readonly' => '','autocomplete' => 'off']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        </div>
                    </div>


                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> {{__('label.GENERATE')}} 
                        </button>
                    </div>

                </div>
            </div>
            {{ Form::close() }}

            @if (Request::get('generate') == 'true')

            <div class="table-responsive">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter bold">{{__('label.SL_NO')}}</th>
                                <th class="text-center vcenter bold">{{__('label.DATE')}}</th>
                                <th class="text-center vcenter bold">{{__('label.FILE')}}</th>
                                @if(!empty($userAccessArr[85][9]))
                                <th class='text-center vcenter bold'>{{__('label.ACTION')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($filedata))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($filedata as $file)
                            <tr>
                                <td class="text-center vcenter">{{++$sl}}</td>
                                <td class="text-center vcenter">{{date('d F Y', strtotime(date('Y-m-d', $file['filetime'])))}}</td>
                                <td class="text-center vcenter">{{$file['filename']}}</td>
                                @if(!empty($userAccessArr[85][9]))
                                <td class="text-center vcenter">
                                    {{ Form::open(array('url' => 'dbBackup/downloadFile', 'class' => 'download-file-form')) }}
                                    {{ Form::hidden('_method', 'POST') }}
                                    {{ Form::hidden('file_name', !empty($file['filename']) ? $file['filename'] : '') }}
                                    {{ Form::hidden('file_path', $file['filepath']) }}
                                    <button type="submit" id="print" class="btn btn-sm green tooltips download-file keep-download-log" data-file-path="{{$file['filepath']}}" data-file-name="{{!empty($file['filename']) ? $file['filename'] : ''}}" title="Download" >
                                        <i class="fa fa-download"></i>
                                    </button>
                                    {{ Form::close() }}
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="vcenter" colspan="6">{{__('label.NO_DATA_FOUND')}}</td>
                            </tr>
                            @endif 
                        </tbody>
                    </table>

                </div>
            </div>
            @endif
        </div>


    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $(document).on("click", '.download-file', function (e) {
            e.preventDefault();
            var form = $(this).parents('form');
            form.submit();
        });
        $(document).on("click", '.keep-download-log', function () {
            var downloadedFile = $(this).attr('data-file-name');
            var downloadedFilePath = $(this).attr('data-file-path');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{URL::to('dbBackup/download')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    downloaded_file: downloadedFile,
                    downloaded_file_path: downloadedFilePath,
                },
                success: function (res) {
                    toastr.success(res.message, res.heading, options);
                    setTimeout(2000);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        });
    });

</script>
@stop

