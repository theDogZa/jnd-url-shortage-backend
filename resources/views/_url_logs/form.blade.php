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
                @if(!isset($url_log))
                {{ ucfirst(__('url_logs.head_title.add')) }}
                @else
                {{ ucfirst(__('url_logs.head_title.edit')) }}
                @endif
                <small> </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <form action="{{ url('/url_logs'.( isset($url_log) ? '/' . $url_log->id : '')) }}" method="POST" class="needs-validation" enctype="application/x-www-form-urlencoded" id="form" novalidate>
                {{ csrf_field() }}
                @if(isset($url_log))
                <input type="hidden" name="_method" value="PUT">
                @endif
                <div class="row form-group">
                    @if($arrShowField['short_url']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="short_url">{{ucfirst(__('url_logs.short_url.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_logs.short_url.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_logs.short_url.popover.title')) ,'content'=> ucfirst(__('url_logs.short_url.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="short_url" name="short_url" required  value="{{ @$short_url }}" placeholde="{{__('url_logs.short_url.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_logs.short_url.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['original_url']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="original_url">{{ucfirst(__('url_logs.original_url.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_logs.original_url.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_logs.original_url.popover.title')) ,'content'=> ucfirst(__('url_logs.original_url.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="original_url" name="original_url" required  value="{{ @$original_url }}" placeholde="{{__('url_logs.original_url.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_logs.original_url.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['ip']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="ip">{{ucfirst(__('url_logs.ip.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_logs.ip.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_logs.ip.popover.title')) ,'content'=> ucfirst(__('url_logs.ip.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="ip" name="ip" required  value="{{ @$ip }}" placeholde="{{__('url_logs.ip.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_logs.ip.label')) ])
                    </div>
                    @endif
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col">
                        @include('components._btn_back')
                        @include('components._btn_submit_form')
                        @include('components._btn_reset_form')
                    </div>
                </div>
            </form>
            <!-- END Content Data -->
        </div>
    </div>
    <!-- END Content Main -->
</div>
<!-- END Page Content -->
@endsection
@section('css_after')
<link rel="stylesheet" id="css-flatpickr" href="{{ asset('/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('js_after')
<script src="{{ asset('/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script>

    (function() {
      'use strict';
      window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();

    $(function($) {
        $(".input-datetime").flatpickr({
            allowInput: true,
            enableTime: true,
            time_24hr: true
            // minTime: "16:00",
            // maxTime: "22:30",
        });
        $('.input-datetime').keypress(function() {
            return false;
        });
        $(".input-date").flatpickr({
            allowInput: true,
            // altFormat: "F j, Y",
            // dateFormat: "Y-m-d",
            // minDate: "today",
            //maxDate: new Date().fp_incr(14) // 14 days from now
        });
        $('.input-date').keypress(function() {
            return false;
        });
        $(".input-time").flatpickr({
            allowInput: true,
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
            // minTime: "16:00",
            // maxTime: "22:30",
        });
        $('.input-time').keypress(function() {
            return false;
        });

        $('.input-clear').click(function() {
            $(this).closest('.input-group').find('input').val("");     
        });
        $('.input-toggle').click(function() {
            var idInput = '#'+$(this).closest('.input-group').find('input').attr('id');    
            const calendar = document.querySelector(idInput)._flatpickr;
            calendar.toggle();
        });
    });
</script>
@endsection



<!--
/** 
 * CRUD Laravel
 * Master ฺBY Kepex  =>  https://github.com/kEpEx/laravel-crud-generator
 * Modify/Update BY PRASONG PUTICHANCHAI
 * 
 * Latest Update : 06/04/2018 13:51
 * Version : ver.1.00.00
 *
 * File Create : 2022-10-21 09:23:04 *
 */
-->