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
                setInterval(fetchLatestData, 3000);
            });
        </script>
    </div>
    <div class="table">
        <div id="temperatureChart" style="width: 600px; height: 400px;"></div>
        <div id="humidityChart" style="width: 600px; height: 400px;"></div>

        <script>
            let temperatureChart, humidityChart;
            const baseUrl = '{{ url('/') }}';

            async function requestData() {
                let endpoint = `${baseUrl}/latest-dht11`;

                try {
                    const result = await fetch(endpoint, {
                        method: 'GET',
                    });
                    if (result.ok) {
                        const data = await result.json();
                        console.log('Fetched data:', data);  // Debugging: log fetched data

                        if (data) {
                            let x = new Date(data.created_at).getTime();
                            let tempC = Number(data.temp_c);
                            let humid = Number(data.humid);

                            console.log(`Temperature X: ${x}, Y: ${tempC}`); // Debugging: log each point
                            console.log(`Humidity X: ${x}, Y: ${humid}`); // Debugging: log each point

                            // Add data to charts
                            temperatureChart.series[0].addPoint([x, tempC], true, temperatureChart.series[0].data.length > 20);
                            humidityChart.series[0].addPoint([x, humid], true, humidityChart.series[0].data.length > 20);

                            // Uncomment to periodically fetch new data
                            setTimeout(requestData, 3000); // Fetch data every 3 seconds
                        } else {
                            console.error('API response is empty');
                        }
                    } else {
                        console.error('Failed to fetch data from API');
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

            window.addEventListener('load', function() {
                temperatureChart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'temperatureChart',
                        type: 'spline',
                        events: {
                            load: requestData
                        }
                    },
                    title: {
                        text: 'Temperature'
                    },
                    xAxis: {
                        type: 'datetime',
                        tickPixelInterval: 150,
                        maxZoom: 20 * 1000
                    },
                    yAxis: {
                        minPadding: 0.2,
                        maxPadding: 0.2,
                        title: {
                            text: 'Temperature (°C)',
                            margin: 80
                        }
                    },
                    series: [{
                        name: 'Temperature',
                        data: []
                    }]
                });

                humidityChart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'humidityChart',
                        type: 'spline',
                        events: {
                            load: requestData
                        }
                    },
                    title: {
                        text: 'Humidity'
                    },
                    xAxis: {
                        type: 'datetime',
                        tickPixelInterval: 150,
                        maxZoom: 20 * 1000
                    },
                    yAxis: {
                        minPadding: 0.2,
                        maxPadding: 0.2,
                        title: {
                            text: 'Humidity (%)',
                            margin: 80
                        }
                    },
                    series: [{
                        name: 'Humidity',
                        data: []
                    }]
                });
            });
        </script>
    </div>
@endsection
