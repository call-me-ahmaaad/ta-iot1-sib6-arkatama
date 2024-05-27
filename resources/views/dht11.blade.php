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
            <p>{{ $temp_f }}°F</p>
        </div>
        <div class="card" id="kelvin">
            <p id="unit">Kelvin:</p>
            <p>{{ $temp_k }}°K</p>
        </div>
        <div class="card" id="humid">
            <p id="unit">Humid:</p>
            <p><span id="humid_value">{{ $humid }}%</p>
        </div>

        <script>
            $(document).ready(function() {
                function fetchLatestTemp() {
                    $.ajax({
                        url: '/latest-temp',
                        method: 'GET',
                        success: function(data) {
                            $('#temp_c').text(data.temp_c + '°C');
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
                setInterval(fetchLatestTemp, 1000);

                // Fetch the latest humidity every 5 seconds
                setInterval(fetchLatestHumid, 1000);
            });
        </script>
    </div>
    <div class="table">

    </div>
@endsection
