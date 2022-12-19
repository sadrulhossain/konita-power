<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title text-center">
            @lang('label.SET_QUALITY_FOR_THIS_PRODUCT', ['product' => !empty($productInfo->name)?$productInfo->name:''])
        </h4>
    </div>
    <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'saveTechDataSheetForm')) !!}
        {!! Form::hidden('product_id',$request->product_id) !!}
        {{csrf_field()}}
        @if(!$brandArr->isEmpty())
        <div class="form-body">
            <div class="row margin-top-10 margin-bottom-10">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">
                    <div class="alert alert-info">
                        <p>
                            <i class="fa fa-info-circle"></i>
                            @lang('label.PLEASE_PUT_TICK_TO_ADD_TECHNICAL_DATASHEET_TDS_TO_THE_BRAND_S')
                        </p>
                    </div>
                </div>
            </div>
            @foreach($brandArr as $brand)
            <?php
            $checked = '';
            $display = 'none';
            if (!empty($previousDataSheetArr) && array_key_exists($brand->id, $previousDataSheetArr)) {
                $checked = 'checked';
                $display = 'block';
            }
            ?>
            <div class="row margin-bottom-25">
                <div class="col-md-12">
                    <div class="ds-border-style">
                        <div class="ds-info-style">
                            <div class="ds-info-div margin-0">
                                <div class="md-checkbox vcenter module-check">
                                    {!! Form::checkbox('brand['.$brand->id.']',$brand->id,$checked, ['id' => 'brandId_'.$brand->id, 'class'=> 'md-check ds-brand-check']) !!}
                                    <label for="{{ 'brandId_'.$brand->id }}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                    @if(!empty($brand->logo) && File::exists(URL::to('/').'/public/uploads/brand/'.$brand->logo))
                                    <img class="pictogram-min-space vcenter tooltips" width="20" height="20" src="{{URL::to('/')}}/public/uploads/brand/{{ $brand->logo }}" alt="{{ $brand->name}}" title="{{ $brand->name }}"/>
                                    @else 
                                    <img class="vcenter tooltips" width="20" height="20" src="{{URL::to('/')}}/public/img/no_image.png" alt="{{ $brand->name}}" title="{{ $brand->name }}"/>
                                    @endif
                                    <span class="vcenter">{!! $brand->name ?? '' !!}</span>
                                </div>
                            </div>
                        </div>
                        {!! Form::hidden('brand_name['.$brand->id.']', $brand->name ?? '') !!}
                        <div id="{{ 'dsDiv_'.$brand->id }}" style="display: {{ $display }};">
                            <div class="form-group">
                                <div class="col-md-12">
                                    @if(!empty($previousDataSheetArr[$brand->id]))
                                    <?php $dataSheetCounter = 0; ?>
                                    @foreach($previousDataSheetArr[$brand->id] as  $dataSheetId => $dataSheet)
                                    <div class="row tech-data-sheet-div">
                                        <div class="col-md-4">
                                            <label class="control-label" for="{!! 'title_'.$brand->id.'_'.$dataSheetId !!}">@lang('label.TITLE'):<span class="text-danger">*</span></label>
                                            {!! Form::text('title['.$brand->id.']['.$dataSheetId.']', $dataSheet['title'], ['id'=> 'title_'.$brand->id.'_'.$dataSheetId, 'class' => 'form-control','autocomplete' => 'off']) !!}
                                            <span class="text-danger">{!! $errors->first('title['.$brand->id.']['.$dataSheetId.']') !!}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                            <br/>
                                            @if(!empty($dataSheet['file']))
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn green btn-file">
                                                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                    <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                    {!! Form::file('data_sheet_file['.$brand->id.']['.$dataSheetId.']',null,['id'=> 'dataSheetFile_'.$brand->id.'_'.$dataSheetId]) !!}
                                                    {!! Form::hidden('prev_data_sheet['.$brand->id.']['.$dataSheetId.']', $dataSheet['file']) !!}
                                                </span>
                                                <a href="{{URL::to('public/uploads/techDataSheet/'.$dataSheet['file'])}}"
                                                   class="btn yellow-crusta btn-md tooltips" title="Technical Data Sheet Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                                <span class="fileinput-filename">{!!$dataSheet['file']!!}</span>&nbsp;
                                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                            </div>
                                            <div class="clearfix">
                                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.TECNICAL_DATA_SHEET_FILE_FORMAT_SIZE')
                                            </div>
                                            @else
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn green btn-file">
                                                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                    <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                    {!! Form::file('data_sheet_file['.$brand->id.']['.$dataSheetId.']',null,['id'=> 'dataSheetFile_'.$brand->id.'_'.$dataSheetId]) !!}
                                                </span>
                                                <span class="fileinput-filename"></span>&nbsp;
                                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                            </div>
                                            <div class="clearfix">
                                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.TECNICAL_DATA_SHEET_FILE_FORMAT_SIZE')
                                            </div>
                                            @endif
                                        </div>
                                        @if($dataSheetCounter == 0)
                                        <div class="col-md-1 margin-top-27">
                                            <button class="btn btn-inline green-haze add-data-sheet tooltips" data-id="{!! $brand->id !!}" data-placement="right" title="@lang('label.ADD_NEW_ROW_OF_ETS_INFO')" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        @else
                                        <div class="col-md-1 margin-top-27">
                                            <button class="btn btn-danger remove tooltips" title="Remove" type="button">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    <?php $dataSheetCounter++; ?>
                                    @endforeach
                                    @else
                                    <?php
                                    $v3 = 'd' . uniqid();
                                    ?>
                                    <div class="row tech-data-sheet-div">
                                        <div class="col-md-4">
                                            <label class="control-label" for="{!! 'title_'.$brand->id.'_'.$v3 !!}">@lang('label.TITLE'):<span class="text-danger">*</span></label>
                                            {!! Form::text('title['.$brand->id.']['.$v3.']', null, ['id'=> 'title_'.$brand->id.'_'.$v3, 'class' => 'form-control','autocomplete' => 'off']) !!}
                                            <span class="text-danger">{!! $errors->first('title['.$brand->id.']['.$v3.']') !!}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                            <br/>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn green btn-file">
                                                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                    <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                    {!! Form::file('data_sheet_file['.$brand->id.']['.$v3.']',['id'=> 'dataSheetFile_'.$brand->id.'_'.$v3]) !!}
                                                </span>
                                                <span class="fileinput-filename"></span> &nbsp;
                                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                            </div>
                                            <div class="clearfix">
                                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.TECNICAL_DATA_SHEET_FILE_FORMAT_SIZE')
                                            </div>
                                        </div>


                                        <div class="col-md-1 margin-top-27">
                                            <button class="btn btn-inline green-haze add-data-sheet tooltips" data-id="{!! $brand->id !!}" data-placement="right" title="@lang('label.ADD')" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="newDataSheetRow{{$brand->id}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row div-padding-20 margin-top-10">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger">
                    <p>
                        <i class="fa fa-warning"></i>
                        @lang('label.NO_BRAND_FOUND_RELATED_TO_THIS_PRODUCT').
                    </p>
                </div>
            </div>
        </div>
        @endif
        {!! Form::close() !!}
        <!-- END FORM-->
    </div>
    <div class="modal-footer">
        @if(!$brandArr->isEmpty())
        <button type="button" class="btn btn-primary" id="saveTecnicalDataSheet">@lang('label.CONFIRM_SUBMIT')</button>
        @endif
        <button type="button" data-dismiss="modal" data-placement="top" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    <div id="showPricingHistory"></div>
</div>

<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $(".tooltips").tooltip();

    $(".ds-brand-check").on("click", function () {
        var brandId = $(this).val();
        if ($(this).prop('checked')) {
            $("#dsDiv_" + brandId).show(1000);
        } else {
            $("#dsDiv_" + brandId).hide(1000);
        }
    });

    //new datasheet row
    $(".add-data-sheet").on("click", function (e) {
        e.preventDefault();
        var brandId = $(this).attr("data-id");
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


        $.ajax({
            url: "{{URL::to('product/newDataSheetRow')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            data: {
                brand_id: brandId,
            },
            success: function (res) {
                $("#newDataSheetRow" + brandId).prepend(res.html);
            },
        });

    });

    $('.remove').on('click', function () {
        $(this).parent().parent().remove();
        return false;
    });

    //save technical data sheets for product
    $("#saveTecnicalDataSheet").on("click", function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You can not undo this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, save',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: true,
            closeOnCancel: true},
                function (isConfirm) {
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
                        var formData = new FormData($('#saveTechDataSheetForm')[0]);
                        $.ajax({
                            url: "{{URL::to('product/setProductQuality')}}",
                            type: "POST",
                            dataType: 'json', // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (res) {
                                toastr.success(res.data, res.message, options);
                                location.reload();
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
                                App.unblockUI();
                            }
                        });
                    }
                });

    });

});
</script>