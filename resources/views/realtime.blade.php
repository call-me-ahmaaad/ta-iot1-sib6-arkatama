<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gas Gauge</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> <!-- Modul aksesibilitas -->
</head>
<body>
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
            },
            accessibility: {  // Menambahkan konfigurasi aksesibilitas
                enabled: true
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
</body>
</html>
