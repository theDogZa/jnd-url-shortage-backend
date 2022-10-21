<div class="modal fade" tabindex="-1" aria-labelledby="upload" id="upload" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="needs-validation" enctype="multipart/form-data" id="form" novalidate>
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">upload products excel</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content"> 
                    {{ csrf_field() }}
                    <div class="row form-group">
                        <div class="col-12 mb-3">
                            <label for="upload_file">Excel File
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="upload_file" class="form-control" id="upload_file" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                            @include('components._invalid_feedback',['required'=>'','message'=>ucfirst(__('products.upload_file.label')) ])
                        </div>
                        <div class="col-12 mb-3">
                            <label for="upload_file">File Description</label>
                        <textarea class="form-control" id="file_description" name="file_description" rows="3"   placeholde="{{__('upload.file_description.placeholder')}}">{{@$file_description}}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="upload_file">Set Image</label>
                            <div class="custom-control custom-checkbox mb-5">
                                <label class="css-control css-control-lg css-control-primary css-checkbox">
                                    <input type="checkbox" class="css-control-input" name="set_image" id="set_image" value="1" checked="">
                                    <span class="css-control-indicator"></span> เลือกรูปอัตโนมัติ
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- @include('components._btn_submit_form') --}}
                
                <button type="submit" class="btn btn-primary min-width-125 js-click-ripple-enabled" id="uploadBTN" data-toggle="click-ripple" style="overflow: hidden; position: relative; z-index: 1;">
                    <i class="fa fa-save mr-2"></i> Save </button>
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button> 
                </div>
            </form>
        </div>
    </div>
</div>
