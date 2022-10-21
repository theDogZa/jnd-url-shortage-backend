@extends('layouts.backend')
@section('content')
<!-- Page Content -->
<div class="container-fluid">
    @include('components._breadcrumb',['isSearch'=> false])
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_url_logs')}} mr-2"></i>{{ ucfirst(__('url_logs.heading')) }}
        <div class="bock-sub-menu"></div>
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_form')}} mr-2"></i>
                {{ ucfirst(__('url_logs.head_title.view')) }}
                <small> </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <div class="row form-group">
                @if($arrShowField['short_url']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="short_url">{{ucfirst(__('url_logs.short_url.label'))}}
                        @if(__('url_logs.short_url.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_logs.short_url.popover.title')) ,'content'=> ucfirst(__('url_logs.short_url.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="short_url" name="short_url" disabled value="{{ @$url_log->short_url }}" placeholde="{{__('url_logs.short_url.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['original_url']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="original_url">{{ucfirst(__('url_logs.original_url.label'))}}
                        @if(__('url_logs.original_url.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_logs.original_url.popover.title')) ,'content'=> ucfirst(__('url_logs.original_url.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="original_url" name="original_url" disabled value="{{ @$url_log->original_url }}" placeholde="{{__('url_logs.original_url.placeholder')}}">
                </div>
                @endif
                @if($arrShowField['ip']==true)
                <div class="{{config('theme.layout.view')}}">
                    <label for="ip">{{ucfirst(__('url_logs.ip.label'))}}
                        @if(__('url_logs.ip.popover.title') != "")
                        @include('components._popover_info', ['title' => ucfirst(__('url_logs.ip.popover.title')) ,'content'=> ucfirst(__('url_logs.ip.popover.content'))])
                        @endif
                    </label>
                    <input type="text" class="form-control" id="ip" name="ip" disabled value="{{ @$url_log->ip }}" placeholde="{{__('url_logs.ip.placeholder')}}">
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
 * File Create : 2022-10-21 09:23:04 *
 */
-->