@extends('layouts.sensorPage')
@section('css')
    <link rel="stylesheet" href="/css/sensorPageDht.css">
@endsection
@section('navbar')

@endsection
@section('container')
    <div class="label">
        <div class="card" id="celcius">
            <p id="unit">Celcius:</p>
            <p><span id="temp_c">{{ $temp_c }}°C</span></p>
        </div>
        <div class="card" id="farenheit">
            <p id="unit">Farenheit:</p>
            <p><span id="temp_f">{{ $temp_f }}°F</span></p>
        </div>
        <div class="card" id="kelvin">
            <p id="unit">Kelvin:</p>
            <p><span id="temp_k">{{ $temp_k }}°K</span></p>
        </div>
        <div class="card" id="humid">
            <p id="unit">Humid:</p>
            <p><span id="humid_value">{{ $humid }}%</p>
        </div>

        <script>
            $(document).ready(function() {
                function fetchLatestTemp_c() {
                    $.ajax({
                        url: '/latest-temp_c',
                        method: 'GET',
                        success: function(data) {
                            $('#temp_c').text(data.temp_c + '°C');
                        },
                        error: function(error) {
                            console.log('Error fetching latest temperature:', error);
                        }
                    });
                }

                function fetchLatestTemp_f() {
                    $.ajax({
                        url: '/latest-temp_f',
                        method: 'GET',
                        success: function(data) {
                            $('#temp_f').text(data.temp_f + '°F');
                        },
                        error: function(error) {
                            console.log('Error fetching latest temperature:', error);
                        }
                    });
                }

                function fetchLatestTemp_k() {
                    $.ajax({
                        url: '/latest-temp_k',
                        method: 'GET',
                        success: function(data) {
                            $('#temp_k').text(data.temp_k + '°K');
                        },
                        error: function(error) {
                            console.log('Error fetching latest temperature:', error);
                        }
                    });
                }

                function fetchLatestHumid() {
                    $.ajax({
                        url: '/latest-humid',
                        method: 'GET',
                        success: function(data) {
                            $('#humid_value').text(data.humid + '%');
                        },
                        error: function(error) {
                            console.log('Error fetching latest humidity:', error);
                        }
                    });
                }

                // Fetch the latest temperature every 5 seconds
                setInterval(fetchLatestTemp_c, 1000);

                setInterval(fetchLatestTemp_f, 1000);

                setInterval(fetchLatestTemp_k, 1000);

                // Fetch the latest humidity every 5 seconds
                setInterval(fetchLatestHumid, 1000);
            });
        </script>
    </div>
    <div class="table">

    </div>
@endsection
