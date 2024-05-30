@extends('layouts.sensorPage')
@section('css')
    <link rel="stylesheet" href="/css/sensorPageDht.css">
@endsection
@section('navbar')

@endsection
@section('container')
    <div class="label">
        <div class="card" id="unit">
            <label for="temperatureUnit">Unit:</label>
            <select id="temperatureUnit" onchange="updateTemperatureChart()">
                <option value="celsius">Celsius (°C)</option>
                <option value="fahrenheit">Fahrenheit (°F)</option>
                <option value="kelvin">Kelvin (°K)</option>
            </select>
        </div>
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
        <div id="temperatureChart"></div>
        <div id="humidityChart"></div>

        <script>
            let temperatureChart, humidityChart;
            const baseUrl = '{{ url('/') }}';
            let temperatureData = [];

            async function requestData() {
                let endpoint = `${baseUrl}/latest-dht11`;

                try {
                    const result = await fetch(endpoint, {
                        method: 'GET',
                    });
                    if (result.ok) {
                        const data = await result.json();
                        console.log('Fetched data:', data);  // Debugging: log fetched data

                        if (data && data.temp_c !== null && data.humid !== null) {
                            let x = new Date(data.created_at).getTime();
                            temperatureData.push({
                                time: x,
                                celsius: Number(data.temp_c),
                                fahrenheit: Number(data.temp_f),
                                kelvin: Number(data.temp_k)
                            });
                            let humid = Number(data.humid);

                            console.log(`Temperature X: ${x}, Y: ${temperatureData}`); // Debugging: log each point
                            console.log(`Humidity X: ${x}, Y: ${humid}`); // Debugging: log each point

                            // Add humidity data to chart
                            humidityChart.series[0].addPoint([x, humid], true, humidityChart.series[0].data.length > 20);

                            // Update temperature chart based on selected unit
                            updateTemperatureChart();

                            // Uncomment to periodically fetch new data
                            setTimeout(requestData, 3000); // Fetch data every 3 seconds
                        } else {
                            console.error('API response is empty or missing required data');
                        }
                    } else {
                        console.error('Failed to fetch data from API');
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

            function updateTemperatureChart() {
                const unit = document.getElementById('temperatureUnit').value;
                let seriesData = temperatureData.map(point => {
                    let temp;
                    switch (unit) {
                        case 'celsius':
                            temp = point.celsius;
                            break;
                        case 'fahrenheit':
                            temp = point.fahrenheit;
                            break;
                        case 'kelvin':
                            temp = point.kelvin;
                            break;
                    }
                    return [point.time, temp];
                });

                let color;
                switch (unit) {
                    case 'celsius':
                        color = '#6488EA';
                        break;
                    case 'fahrenheit':
                        color = '#6fc276';
                        break;
                    case 'kelvin':
                        color = '#ff8242';
                        break;
                }

                temperatureChart.series[0].update({
                    data: seriesData,
                    color: color
                }, true);

                temperatureChart.yAxis[0].setTitle({
                    text: `Temperature (${unit === 'celsius' ? '°C' : unit === 'fahrenheit' ? '°F' : '°K'})`
                });

                // Update color of title and axis titles
                temperatureChart.update({
                    title: {
                        style: {
                            color: color
                        }
                    },
                    yAxis: {
                        title: {
                            style: {
                                color: color
                            }
                        }
                    }
                });
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
                        text: 'Temperature',
                        style: {
                            color: 'blue'
                        }
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
                            margin: 80,
                            style: {
                                color: 'blue'
                            }
                        }
                    },
                    series: [{
                        name: 'Temperature',
                        data: [],
                        color: 'blue'
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
                        text: 'Humidity',
                        style: {
                            color: '#7d54ae'
                        }
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
                            margin: 80,
                            style: {
                                color: '#7d54ae'
                            }
                        }
                    },
                    series: [{
                        name: 'Humidity',
                        data: [],
                        color: '#7d54ae'
                    }]
                });
            });
        </script>
    </div>
@endsection
