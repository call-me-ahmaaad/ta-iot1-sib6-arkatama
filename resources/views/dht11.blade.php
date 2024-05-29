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
                function fetchLatestData() {
                    $.ajax({
                        url: '/latest-dht11',
                        method: 'GET',
                        success: function(data) {
                            $('#temp_c').text(data.temp_c + '°C');
                            $('#temp_f').text(data.temp_f + '°F');
                            $('#temp_k').text(data.temp_k + '°K');
                            $('#humid_value').text(data.humid + '%');
                        },
                        error: function(error) {
                            console.log('Error fetching latest data:', error);
                        }
                    });
                }

                // Fetch the latest data every 1 seconds
                setInterval(fetchLatestData, 1000);
            });
        </script>
    </div>
    <div class="table">

    </div>
@endsection
