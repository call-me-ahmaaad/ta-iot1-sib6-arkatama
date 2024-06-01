@extends('layouts.sensorPage')
@section('css')
    <link rel="stylesheet" href={{URL::asset("/css/sensorPageMq2.css")}}>
@endsection
@section('navbar')

@endsection
@section('container')
    <div class="label">
        <div class="card">
            <p id="unit"></p>
            <p>50 ppm</p>
        </div>
        <div class="gaugeMonitoring">

        </div>
    </div>
    <div class="table">

    </div>
@endsection
