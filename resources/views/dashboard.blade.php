@extends('layouts.backend')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <div class="my-50 text-center">
            <h2 class="font-w700 text-black mb-10"><span class="text-danger">JND</span><span class="">Url</span></h2>
            <h4 class="mt-3 h5 text-muted mb-0">Shorten</h4>
            <h3 class="mt-3 h5 text-muted mb-0">Welcome 
            <span class="font-size-xl text-primary">{{ auth()->user()->username }}</span> to your app.</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-xl-5">
                <div class="block">
                    <div class="block-content">
                        <p class="mt-3 mb-4 pt-4 pb-4 text-muted">
                        your last login : {{ @session('last_login') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="ats-content-chart" class="ats-content pl-2 pr-2 pb-2">
            <canvas id="ats-chart" width="600" height="250"></canvas>
        </div>
        
    </div>
    <!-- END Page Content -->
@endsection
@section('js_after')
<script src="{{ asset('/js/plugins/chartjs/Chart.bundle.min.js') }}"></script>
<script>

    var data = {!! json_encode($chartTime['data']) !!}
    var label = {!! json_encode($chartTime['label']) !!}

    var data1 = {
        labels: label,
        datasets: [{
        label: 'count on cilck Url Shorten by date now',
        data: data,
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
    } 

    var ctx = document.getElementById('ats-chart').getContext('2d');
    var myLineChart = new Chart(ctx, {
    type: 'line',
    data: data1,
    options: {
        scales: {
            yAxes: [{
                stacked: true
            }]
        }
    }
});
</script>
@endsection