<div id="block-images-select">
    <div class="box-show-images-select" id="showimg"></div>
    <input type="text" id="imageIds" name="image_ids" class="form-control input-lg input-images"
        placeholder="Please select an image from the panel below. " data-rule-required="true"
        data-msg-required="You cannot leave this empty." value="{!!@$imageSelect!!}" min="0" max="3">
        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('products.product_unit_id.label')) ])
    <div class="block block-images-list"  id="">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-image mr-2"></i>
                Images Box
                <small> </small>
            </h3>
            <ul class="nav navbar-right panel_toolbox" style="min-width:20px;">
                <li class="mr-2">
                    <button type="button" class="btn-block-option filter-link"  >
                        <i class="fa fa-filter"></i></i>
                    </button>
                </li>
                <li>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                </li>

            </ul>
        </div>
        <div class="block-content clearfix">
            <div id="block-filter" class="clearfix" >
                <div class="col-list-tags">
                    <i class="fa fa-tags" aria-hidden="true"></i> Tags :
                    <button type="button" id="btn-all" class="btn btn-primary btn-all">#All Images</button>
                    @foreach ($imageTags as $imageTag)
                    <button type="button" data-catid="{{@$imageTag->id}}" class="btn btn-default btn-cat" title="{{@$imageTag->tag_description}}">{{@$imageTag->tag_name}}</button>
                    @endforeach
                    <button type="button" id="btn-notag" class="btn btn-default btn-all">#No Tag</button>
                </div>

                {{-- <div class="col-icon-view" style="width:100px">
                    <button type="button" id="btn-s-app" class="btn btn-default mb-1 btn-all">#No Tag</button>
                    <button type="button" id="btn-notag" class="btn btn-default mb-1 btn-all">#No Tag</button>
                    <button type="button" id="btn-notag" class="btn btn-default  btn-all">#No Tag</button>
                </div> --}}

                <div class="col-icon-view" id="box-show-order">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box-icon"><i class="iconsize fa fa-square" aria-hidden="true" data-val="6"></i>
                            </div>
                            <div class="box-icon"><i class="iconsize fa fa-th-large" aria-hidden="true"
                                    data-val="3"></i></div>
                            <div class="box-icon"><i class="iconsize fa fa-th" aria-hidden="true" data-val="2"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="box-text-select-item">
                            <span id="count-image">{!! count($imageLists)!!}</span>
                            Items Found
                            <input type="hidden" id="url_image_user"
                                value="https://admin.charm.catering/index.php/image/listContentImage/">
                            <input type="hidden" name="imageIdsOld" value="{!!@$imageSelect!!}">
                        </div>
                        <!--/.box-text-select-item-->
                    </div>
                </div>
            </div>
            
            {{-- <div id="block-filter" class="clearfix" >
                ll
            </div> --}}

            <div class="box-list">
                @foreach ($imageLists as $image)
                <div class="col-xs-2 square-box">
                    <div style="background-image:url({{@$image->small_path}});" 
                    class="square-content" id="{{@$image->id}}" data-name="{{@$image->name}}"  data-img="{{@$image->small_path}}" 
                     data-count="2" data-imageid="{{@$image->id}}"
                     data-tags="{{@$image->image_tag_id}}">
                        <div class="box-galley-content">
                            {{@$image->name}}
                            {{-- <span data-container="body" title="This image is in use. " 
                            data-toggle="popover" data-placement="top" data-content="" class="box-image-count" data-id="{{@$image->id}}" 
                            data-name="{{@$image->name}}">
                            </span> --}}
                        </div>
                    </div>
                    
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
