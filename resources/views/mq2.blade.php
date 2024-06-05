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
            <p><span id="gas_value">{{ $gas_value }} ppm</span></p>
        </div>

        <div class="gaugeMonitoring">
            <div class="gauge-container">
                <div class="gauge-bar" id="gaugeBar"></div>
            </div>
            <div id="gaugeLabel">0 ppm</div>
        </div>

        <script>
            $(document).ready(function() {
                function fetchLatestMq2() {
                    $.ajax({
                        url: '/latest-mq2',
                        method: 'GET',
                        success: function(data) {
                            var gasValue = data.gas_value;
                            $('#gas_value').text(gasValue + ' ppm');

                            // Update the gauge value
                            var maxGasValue = 4095; // Assuming 1000 ppm is the maximum value for the gauge
                            var gaugeHeight = (gasValue / maxGasValue) * 100;

                            // Determine the color of the gauge bar based on the value
                            var gaugeColor;
                            if (gasValue <= 300) {
                                gaugeColor = '#6fc276'; // Green for safe levels
                            } else if (gasValue <= 1400) {
                                gaugeColor = '#ffe37a'; // Yellow for caution
                            } else {
                                gaugeColor = '#f94449'; // Red for danger
                            }

                            // Update the gauge bar with chaining
                            $('#gaugeBar')
                                .css('height', gaugeHeight + '%')
                                .css('background-color', gaugeColor);

                            // Update the gauge label
                            $('#gaugeLabel').text(gasValue + ' ppm');
                        },
                        error: function(error) {
                            console.log('Error fetching latest gas data:', error);
                        }
                    });
                }

                // Fetch the latest data every 3 seconds
                setInterval(fetchLatestMq2, 3000);
            });
        </script>
    </div>
    <div class="table">
        <div id="gas_container"></div>

        <script>
            let gasChart;
            const baseUrl = '{{ url('/') }}';
            let lastTimestamp = null;

            async function requestData() {
                let endpoint = `${baseUrl}/latest-mq2`;

                try {
                    const result = await fetch(endpoint, {
                        method: 'GET',
                    });
                    if (result.ok) {
                        const data = await result.json();
                        console.log('Fetched data:', data);  // Debugging: log fetched data

                        if (data && data.gas_value !== null && data.created_at !== null) {
                            let timestamp = new Date(data.created_at).getTime();
                            let value = Number(data.gas_value);

                            // Check if the data is new based on created_at
                            if (timestamp !== lastTimestamp) {
                                lastTimestamp = timestamp;

                                console.log(`Gas Timestamp: ${timestamp}, Value: ${value}`); // Debugging: log each point

                                // Update gas data to chart
                                gasChart.series[0].addPoint([timestamp, value], true, gasChart.series[0].data.length > 20);

                                // Update the value displayed
                                $('#gas_value').text(data.gas_value + ' ppm');
                            }

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

            window.addEventListener('load', function() {
                gasChart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'gas_container',
                        type: 'spline',
                        events: {
                            load: requestData
                        }
                    },
                    title: {
                        text: 'Live Gas Concentration Data',
                        style: {
                            color: '#f94449'
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
                            text: 'Gas Concentration (ppm)',
                            margin: 80,
                            style: {
                                color: '#f94449'
                            }
                        }
                    },
                    series: [{
                        name: 'Gas Concentration',
                        data: [],
                        color: '#f94449'
                    }]
                });
            });
        </script>
    </div>
@endsection
