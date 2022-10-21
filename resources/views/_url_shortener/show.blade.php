@extends('layouts.backend')
@section('content')
<!-- Page Content -->
<div class="container-fluid">
    @include('components._breadcrumb',['isSearch'=> false])
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_url_shortener')}} mr-2"></i>{{ ucfirst(__('url_shortener.heading')) }}
        <div class="bock-sub-menu"></div>
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_form')}} mr-2"></i>
                {{ ucfirst(__('url_shortener.head_title.view')) }}
                <small> </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <div class="row form-group">
                @if($arrShowField['short_url']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="short_url">{{ucfirst(__('url_shortener.short_url.label'))}}
                        @if(__('url_shortener.short_url.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.short_url.popover.title')) ,'content'=> ucfirst(__('url_shortener.short_url.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="short_url" name="short_url" disabled value="{{ @$urlshortener->short_url }}" placeholde="{{__('url_shortener.short_url.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['original_url']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="original_url">{{ucfirst(__('url_shortener.original_url.label'))}}
                        @if(__('url_shortener.original_url.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.original_url.popover.title')) ,'content'=> ucfirst(__('url_shortener.original_url.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="original_url" name="original_url" disabled value="{{ @$urlshortener->original_url }}" placeholde="{{__('url_shortener.original_url.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['ip']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="ip">{{ucfirst(__('url_shortener.ip.label'))}}
                        @if(__('url_shortener.ip.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.ip.popover.title')) ,'content'=> ucfirst(__('url_shortener.ip.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="ip" name="ip" disabled value="{{ @$urlshortener->ip }}" placeholde="{{__('url_shortener.ip.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['count']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="count">{{ucfirst(__('url_shortener.count.label'))}}
                        @if(__('url_shortener.count.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.count.popover.title')) ,'content'=> ucfirst(__('url_shortener.count.popover.content'))])
                        @endif
                    </label>
                    <input type="number" class="form-control" id="count" name="count" disabled value="{{@$urlshortener->count}}" placeholde="{{__('url_shortener.count.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['created_at']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="created_at">{{ucfirst(__('url_shortener.created_at.label'))}}
                        @if(__('url_shortener.created_at.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.created_at.popover.title')) ,'content'=> ucfirst(__('url_shortener.created_at.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="created_at" name="created_at" disabled value="{{@$urlshortener->created_at}}" placeholde="{{__('url_shortener.created_at.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['active']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="active">{{ucfirst(__('url_shortener.active.label'))}}
                        @if(__('url_shortener.active.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_shortener.active.popover.title')) ,'content'=> ucfirst(__('url_shortener.active.popover.content'))])
                        @endif
                    </label>
                    <div>
                        <label class="css-control css-control-lg css-control-success css-radio">
                            <input type="radio" class="css-control-input" value="1" name="active" disabled {!! ( @$urlshortener->active=='1' ? 'checked' : '' ) !!}>
                            <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.true'))}}
                        </label>
                        <label class="css-control css-control-lg css-control-danger css-radio">
                            <input type="radio" class="css-control-input" value="0" name="active" disabled {!! ( @$urlshortener->active!='1' ? 'checked' : '' ) !!}>
                            <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.false'))}}
                        </label>
                    </div>
                </div>
                @endif
            </div>
            <hr>
                <div class="row mb-3">
                    <div class="col">
                        @include('components._btn_back')
                    
                    </div>
                </div>
            <!-- END Content Data -->
        </div>
       
    </div>
    <!-- END Content Main -->
</div>
<!-- END Page Content -->
@endsection
@section('css_after')

@endsection
@section('js_after')

@endsection



<!--
/** 
 * CRUD Laravel
 * Master ฺBY Kepex  =>  https://github.com/kEpEx/laravel-crud-generator
 * Modify/Update BY PRASONG PUTICHANCHAI
 * 
 * Latest Update : 18/09/2020 10:51
 * Version : ver.1.00.00
 *
 * File Create : 2022-10-18 13:58:54 *
 */
-->