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
                @if(!isset($urlshortener))
                {{ ucfirst(__('url_shortener.head_title.add')) }}
                @else
                {{ ucfirst(__('url_shortener.head_title.edit')) }}
                @endif
                <small> </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <form action="{{ url('/url_shortener'.( isset($urlshortener) ? '/' . $urlshortener->id : '')) }}" method="POST" class="needs-validation" enctype="application/x-www-form-urlencoded" id="form" novalidate>
                {{ csrf_field() }}
                @if(isset($urlshortener))
                <input type="hidden" name="_method" value="PUT">
                @endif
                <div class="row form-group">
                    @if($arrShowField['original_url']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="original_url">{{ucfirst(__('url_shortener.original_url.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.original_url.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.original_url.popover.title')) ,'content'=> ucfirst(__('url_shortener.original_url.popover.content'))])
                            @endif
                        </label>
                        <div class="input-group">
                        <input type="text" class="form-control" id="original_url" name="original_url" required  value="{{ @$original_url }}" placeholde="{{__('url_shortener.original_url.placeholder')}}">
                        <button type="button" id="btn_gen_url_shorten" class="btn btn-secondary">Submit</button>
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_shortener.original_url.label')) ])
                        </div>

                    </div>
                    @endif
                    @if($arrShowField['short_url']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="short_url">{{ucfirst(__('url_shortener.short_url.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.short_url.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.short_url.popover.title')) ,'content'=> ucfirst(__('url_shortener.short_url.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="short_url" name="short_url" required  value="{{ @$short_url }}" placeholde="{{__('url_shortener.short_url.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_shortener.short_url.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['ip']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="ip">{{ucfirst(__('url_shortener.ip.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.ip.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.ip.popover.title')) ,'content'=> ucfirst(__('url_shortener.ip.popover.content'))])
                            @endif
                        </label>
                        <input type="text" class="form-control" id="ip" name="ip" required  value="{{ @$ip }}" placeholde="{{__('url_shortener.ip.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_shortener.ip.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['count']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="count">{{ucfirst(__('url_shortener.count.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.count.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.count.popover.title')) ,'content'=> ucfirst(__('url_shortener.count.popover.content'))])
                            @endif
                        </label>
                        <input type="number" class="form-control" id="count" name="count" required  value="{{@$count}}" placeholde="{{__('url_shortener.count.placeholder')}}">
                        @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_shortener.count.label')) ])
                    </div>
                    @endif
                    @if($arrShowField['created_at']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="created_at">{{ucfirst(__('url_shortener.created_at.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.created_at.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.created_at.popover.title')) ,'content'=> ucfirst(__('url_shortener.created_at.popover.content'))])
                            @endif
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control input-datetime bg-white js-flatpickr-enabled flatpickr-input" required  id="created_at" name="created_at" value="{{@$created_at}}" data-default-date="{{@$created_at}}">
                            <div class="input-group-append">
                                <span class="input-group-text input-toggle" title="toggle">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <span class="input-group-text input-clear" title="clear">
                                    <i class="fa fa-close"></i>
                                </span>
                            </div>
                            @include('components._invalid_feedback',['required'=>'required','message'=>ucfirst(__('url_shortener.created_at.label')) ])
                        </div>
                    </div>
                @endif
                    @if($arrShowField['active']==true)
                    <div class="{{config('theme.layout.form')}}">
                        <label for="active">{{ucfirst(__('url_shortener.active.label'))}}
                            <span class="text-danger">*</span>
                            @if(__('url_shortener.active.popover.title') != "")
                            @include('components._popover_info', ['title' => ucfirst(__('url_shortener.active.popover.title')) ,'content'=> ucfirst(__('url_shortener.active.popover.content'))])
                            @endif
                        </label>
                        <div>
                            <label class="css-control css-control-lg css-control-success css-radio">
                                <input type="radio" class="css-control-input" value="1" name="active" {!! ( @$active=='1' ? 'checked' : '' ) !!}>
                                <span class="css-control-indicator"></span> {{ucfirst(__('url_shortener.active.text_radio.true'))}}
                            </label>
                            <label class="css-control css-control-lg css-control-danger css-radio">
                                <input type="radio" class="css-control-input" value="0" name="active" {!! ( @$active!='1' ? 'checked' : '' ) !!}>
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
        $('.input-toggle').click(function() {
            var idInput = '#'+$(this).closest('.input-group').find('input').attr('id');    
            const calendar = document.querySelector(idInput)._flatpickr;
            calendar.toggle();
        });


        $(document).on("click", "#btn_gen_url_shorten", async function () {

            let originalUrl = $("#original_url").val();

            var shortUrl = await genUrlShorten(originalUrl)

            console.log(shortUrl)
            $("#short_url").val(shortUrl)
        });

});

    async function genUrlShorten(originalUrl) {

        var url = "/api/v1/url-shorten-be"
            res = $.post(url, {
                    'url': originalUrl,
            })
            .then(function (response) {
                if (response.status.code === 200) {

                    return response.data;
                } else {
                    return false;
                }

                return response;
            })
            .catch(function (err) {
                return false;
            });

            return await res;
    }
</script>
@endsection
