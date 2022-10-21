<div class="overlay"></div>
<div class="col position-relative" style="z-index: 101">
    <div class="position-absolute block block-rounded block-search mr-2" id="block-search" style="display: none; margin-top: -67px !important;">
        <form action="{{url()->current()}}" method="get" class="form-search" enctype="application/x-www-form-urlencoded">
            <div class="block-header {{config('theme.layout.main_block_search_header')}}">
                <h3 class="block-title">
                    <i class="{{config('theme.icon.advanced_search')}}"></i> {{ ucfirst(__('url_shortener.head_title.search')) }}
                </h3>
                <div class="block-options"></div>
            </div>
            <div class="block-content">
                <div class="row">
                    @if($arrShowField['short_url']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="short_url">{{ucfirst(__('url_shortener.short_url.label'))}}</label>
                        <input type="text" class="form-control" id="short_url" name="short_url" value="{{@$search->short_url}}">
                    </div>
                    @endif
                    @if($arrShowField['original_url']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="original_url">{{ucfirst(__('url_shortener.original_url.label'))}}</label>
                        <input type="text" class="form-control" id="original_url" name="original_url" value="{{@$search->original_url}}">
                    </div>
                    @endif
                    @if($arrShowField['ip']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="ip">{{ucfirst(__('url_shortener.ip.label'))}}</label>
                        <input type="text" class="form-control" id="ip" name="ip" value="{{@$search->ip}}">
                    </div>
                    @endif
                    @if($arrShowField['count']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="count">{{ucfirst(__('url_shortener.count.label'))}}</label>
                        <div class="input-daterange input-group">
                            <input type="number" class="form-control" id="count_start" name="count_start" value="{{@$search->count_start}}" placeholder="From">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text font-w600">to</span>
                            </div>
                            <input type="number" class="form-control" id="count_end" name="count_end" value="{{@$search->count_end}}" placeholder="To">
                        </div>
                    </div>
                    @endif
                    @if($arrShowField['active']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="active">{{ucfirst(__('url_shortener.active.label'))}}</label>
                        <div>
                            <label class="css-control css-control-lg css-control-primary css-radio">
                                <input type="radio" class="css-control-input chk_radio_all" id="chk_radio_all" value="" name="active" {!! ( @$search->active != 'Y' && @$search->active!='N' ? 'checked' : '') !!} >
                                <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.all'))}}
                            </label>
                            <label class="css-control css-control-lg css-control-success css-radio">
                                <input type="radio" class="css-control-input" value="1" name="active" {!! ( @$search->active=='1' ? 'checked' : '') !!}>
                                <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.true'))}}
                            </label>
                            <label class="css-control css-control-lg css-control-danger css-radio">
                                <input type="radio" class="css-control-input" value="0" name="active" {!! ( @$search->active=='0' ? 'checked' : '') !!} >
                                <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.false'))}}
                            </label>
                        </div>
                    </div>
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            @include('components.button._submin_search')
                            @include('components.button._reset',['class'=>'btn-sm btn-reset-search'])
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>