@extends('layouts.backend')
@section('content')
<!-- Page Content -->
<div class="container-fluid">
    @include('components._breadcrumb',['isSearch'=> true])
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_url_shortener')}} mr-2"></i>{{ ucfirst(__('url_shortener.heading')) }}
        <div class="bock-sub-menu">
            @permissions('create.url_shortener')
            @include('components.button._add')
            @endpermissions
        </div>
        @if(View::exists('_url_shortener._advanced_search'))
        @include('_url_shortener._advanced_search')
        @endif
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_list')}} mr-2"></i>
                {{ ucfirst(__('url_shortener.head_title.list')) }}
                <small> ( {{$collection->total() }} {{ ucfirst(__('core.data_total_records')) }} ) </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <table class="table {{config('theme.layout.table_list_item')}}">
                <thead class="{{config('theme.layout.table_list_item_head')}}">
                    <tr>
                        <th>{{ucfirst(__('core.th_iteration_number'))}}</th>
                        @if($arrShowField['short_url']==true)
                        <th class="text-center">
                            @sortablelink('short_url',__('url_shortener.short_url.th'))
                        </th>
                        @endif
                        @if($arrShowField['original_url']==true)
                        <th class="text-center">
                            @sortablelink('original_url',__('url_shortener.original_url.th'))
                        </th>
                        @endif
                        @if($arrShowField['ip']==true)
                        <th class="text-center">
                            @sortablelink('ip',__('url_shortener.ip.th'))
                        </th>
                        @endif
                        @if($arrShowField['count']==true)
                        <th class="text-center">
                            @sortablelink('count',__('url_shortener.count.th'))
                        </th>
                        @endif
                        
                        @if($arrShowField['created_at']==true)
                        <th class="text-center">
                            @sortablelink('created_at',__('url_shortener.created_at.th'))
                        </th>
                        @endif
                        @if($arrShowField['active']==true)
                        <th class="text-center">
                            @sortablelink('active',__('url_shortener.active.th'))
                        </th>
                        @endif
                        <th width="10px;" class="text-center">{{ucfirst(__('core.th_actions'))}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($collection->total())
                    @foreach ($collection as $item)
                    <tr id="row_{{$item->id}}">
                        <td>{!! $loop->iteration+(($collection->currentPage()-1)*$collection->perPage()) !!}</td>
                        @if(!empty( $arrShowField['short_url'] ))
                        <td>
                            {!! @$item->short_url !!}
                        </td>
                        @endif
                        @if(!empty( $arrShowField['original_url'] ))
                        <td>
                            {!! Str::limit( @$item->original_url,config('theme.textarea.limit'),config('theme.textarea.end_str')) !!}
                        </td>
                        @endif
                        @if(!empty( $arrShowField['ip'] ))
                        <td>
                            {!! @$item->ip !!}
                        </td>
                        @endif
                        @if(!empty( $arrShowField['count'] ))
                        <td>
                            {!! @$item->count !!}
                        </td>
                        @endif
                        @if(!empty( $arrShowField['created_at'] ))
                        <td>
                            {!! @$item->created_at !!}
                        </td>
                        @endif
                        @if(!empty( $arrShowField['active'] ))
                        <td>
                            @include('components._badge_radio',['val'=>@$item->active,'tTrue'=>ucfirst(__('url_shortener.active.text_radio.true')), 'tFalse'=>ucfirst(__('url_shortener.active.text_radio.false'))])
                        </td>
                        @endif
                        <td class="text-center">
                            <div class="btn-group">
                                @permissions('read.url_shortener')
                                @include('components.button._view')
                                @endpermissions
                                @permissions('update.url_shortener')
                                @include('components.button._edit')
                                @endpermissions
                                @permissions('del.url_shortener')
                                @include('components.button._del')
                                @endpermissions
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" class="text-center">{{ ucfirst(__('core.no_records')) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <!-- END Content Data -->
            @include('components._pagination', ['data' => $collection])
        </div>
    </div>
    <!-- END Content Main -->
</div>
<!-- END Page Content -->
@include('components._form_del',['action'=> 'url_shortener'])
@include('components._notify_message')
@endsection

@section('css_after')
<link rel="stylesheet" id="css-flatpickr" href="{{ asset('/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('js_after')
<script src="{{ asset('/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script>
    $(function($) {
        $(".input-datetime").flatpickr({
            allowInput: true,
            enableTime: true,
            time_24hr: true,
            altInputClass: 'flatpickr-alt',
            // minTime: "16:00",
            // maxTime: "22:30",
            onReady: function(dateObj, dateStr, instance) {
                $('.flatpickr-calendar').each(function() {
                    var $this = $(this);
                    if ($this.find('.flatpickr-clear').length < 1) {
                        $this.append('<div class="flatpickr-clear btn btn-sm btn-rounded btn-warning min-width-125 mb-10">Clear</div>');
                        $this.find('.flatpickr-clear').on('click', function() {
                            instance.clear();
                            instance.close();
                        });
                    }
                });
            }
        });
        $('.input-datetime').keypress(function() {
            return false;
        });
        $(".input-date").flatpickr({
            allowInput: true,
            altInputClass: 'flatpickr-alt',
            // altFormat: "F j, Y",
            // dateFormat: "Y-m-d",
            // minDate: "today",
            //maxDate: new Date().fp_incr(14) // 14 days from now
            onReady: function(dateObj, dateStr, instance) {
                $('.flatpickr-calendar').each(function() {
                    var $this = $(this);
                    if ($this.find('.flatpickr-clear').length < 1) {
                        $this.append('<div class="flatpickr-clear btn btn-sm btn-rounded btn-warning min-width-125 mb-10">Clear</div>');
                        $this.find('.flatpickr-clear').on('click', function() {
                            instance.clear();
                            instance.close();
                        });
                    }
                });
            }
        });
        $('.input-date').keypress(function() {
            return false;
        });
        $(".input-time").flatpickr({
            allowInput: true,
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            altInputClass: 'flatpickr-alt',
            // minTime: "16:00",
            // maxTime: "22:30",
            onReady: function(dateObj, dateStr, instance) {
                $('.flatpickr-calendar').each(function() {
                    var $this = $(this);
                    if ($this.find('.flatpickr-clear').length < 1) {
                        $this.append('<div class="flatpickr-clear btn btn-sm btn-rounded btn-warning min-width-125 mb-10">Clear</div>');
                        $this.find('.flatpickr-clear').on('click', function() {
                            instance.clear();
                            instance.close();
                        });
                    }
                });
            }
        });
        $('.input-time').keypress(function() {
            return false;
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
 * File Create : 2022-10-18 13:58:54 *
 */
-->