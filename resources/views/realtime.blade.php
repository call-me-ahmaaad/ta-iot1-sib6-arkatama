<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik DHT11 Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
</head>
<body>
    <div>
        <h2>Grafik Suhu (Celsius)</h2>
        <div id="temperatureChart"></div>
    </div>
    <div>
        <h2>Grafik Kelembaban</h2>
        <div id="humidityChart"></div>
    </div>

    <div id="container-gauge" style="width: 400px; height: 400px;"></div>
    <p id="gas_value">Loading...</p>

        <script>
            // Inisialisasi chart gauge Highcharts
            var gaugeOptions = {
                chart: {
                    type: 'solidgauge'
                },
                title: null,
                pane: {
                    center: ['50%', '85%'],
                    size: '140%',
                    startAngle: -90,
                    endAngle: 90,
                    background: {
                        backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
                        innerRadius: '60%',
                        outerRadius: '100%',
                        shape: 'arc'
                    }
                },
                tooltip: {
                    enabled: false
                },
                yAxis: {
                    min: 0,
                    max: 1000, // Sesuaikan nilai maksimum
                    stops: [
                        [0.1, '#55BF3B'], // hijau
                        [0.5, '#DDDF0D'], // kuning
                        [0.9, '#DF5353'] // merah
                    ],
                    lineWidth: 0,
                    tickWidth: 0,
                    minorTickInterval: null,
                    tickAmount: 2,
                    title: {
                        y: -70
                    },
                    labels: {
                        y: 16
                    }
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            y: 5,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                }
            };

            var chart = Highcharts.chart('container-gauge', Highcharts.merge(gaugeOptions, {
                yAxis: {
                    title: {
                        text: 'Gas Level'
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Gas Level',
                    data: [0],
                    dataLabels: {
                        format: '<div style="text-align:center"><span style="font-size:25px">{y}</span><br/><span style="font-size:12px;opacity:0.4">ppm</span></div>'
                    },
                    tooltip: {
                        valueSuffix: ' ppm'
                    }
                }]
            }));

            function fetchLatestMq2() {
                $.ajax({
                    url: '/latest-mq2',
                    method: 'GET',
                    success: function(data) {
                        var gasValue = data.gas_value;
                        $('#gas_value').text(gasValue + ' ppm');
                        updateGauge(gasValue);
                    },
                    error: function(error) {
                        console.log('Error fetching latest gas data:', error);
                    }
                });
            }

            function updateGauge(gasValue) {
                chart.series[0].points[0].update(gasValue);
            }

            // Panggil fungsi fetchLatestMq2 untuk pertama kali dan set interval untuk memperbarui data
            fetchLatestMq2();
            setInterval(fetchLatestMq2, 5000);  // Memperbarui setiap 5 detik, bisa disesuaikan
        </script>
    <script>
        let temperatureChart, humidityChart;
        const baseUrl = '{{ url('/') }}';

        async function requestData() {
            let endpoint = `${baseUrl}/api/dht11/latest`;

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
</body>
</html>
