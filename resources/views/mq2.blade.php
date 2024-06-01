@extends('layouts.sensorPage')
@section('css')
    <link rel="stylesheet" href={{URL::asset("/css/sensorPageMq2.css")}}>
@endsection
@section('navbar')

@endsection
@section('container')
    <div class="label">
        <div class="card">
            <p id="unit">Konsentrasi Gas</p>
            <p><span id="gas_value">{{ $gas_value }}°C</span></p>
        </div>

        <script>
            $(document).ready(function() {
                function fetchLatestMq2() {
                    $.ajax({
                        url: '/latest-mq2',
                        method: 'GET',
                        success: function(data) {
                            $('#gas_value').text(data.gas_value + ' ppm');
                        },
                        error: function(error) {
                            console.log('Error fetching latest gas data:', error);
                        }
                    });
                }

                // Fetch the latest data every 1 seconds
                setInterval(fetchLatestMq2, 3000);
            });
        </script>

        <div class="gaugeMonitoring">

        </div>
    </div>
    <div class="table">
        Kenapa gak muncul si?
    </div>
@endsection
