<div class="overlay"></div>
<div class="col position-relative" style="z-index: 101">
    <div class="position-absolute block block-rounded block-search mr-2" id="block-search" style="display: none; margin-top: -67px !important;">
        <form action="{{url()->current()}}" method="get" class="form-search" enctype="application/x-www-form-urlencoded">
            <div class="block-header {{config('theme.layout.main_block_search_header')}}">
                <h3 class="block-title">
                    <i class="{{config('theme.icon.advanced_search')}}"></i> {{ ucfirst(__('url_logs.head_title.search')) }}
                </h3>
                <div class="block-options"></div>
            </div>
            <div class="block-content">
                <div class="row">
                    @if($arrShowField['short_url']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="short_url">{{ucfirst(__('url_logs.short_url.label'))}}</label>
                        <input type="text" class="form-control" id="short_url" name="short_url" value="{{@$search->short_url}}">
                    </div>
                    @endif
                    @if($arrShowField['original_url']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="original_url">{{ucfirst(__('url_logs.original_url.label'))}}</label>
                        <input type="text" class="form-control" id="original_url" name="original_url" value="{{@$search->original_url}}">
                    </div>
                    @endif
                    @if($arrShowField['ip']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="ip">{{ucfirst(__('url_logs.ip.label'))}}</label>
                        <input type="text" class="form-control" id="ip" name="ip" value="{{@$search->ip}}">
                    </div>
                    @endif

                    @if($arrShowField['created_at']==true)
                    <div class="form-group {{config('theme.layout.search')}}">
                        <label for="created_at">{{ucfirst(__('url_logs.created_at.label'))}}</label>
                        <div class="input-daterange input-group js-datepicker-enabled" data-date-format="dd-mm-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            <input type="text" class="form-control input-datetime bg-white js-flatpickr-enabled flatpickr-input" id="created_at_start" name="created_at_start" value="{{@$search->created_at_start}}" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text font-w600">to</span>
                            </div>
                            <input type="text" class="form-control input-datetime bg-white js-flatpickr-enabled flatpickr-input" id="created_at_end" name="created_at_end" value="{{@$search->created_at_end}}" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true">
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