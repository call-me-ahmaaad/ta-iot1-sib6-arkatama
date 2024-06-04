{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href={{URL::asset("/css/dashboard.css")}}>
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> <!-- Modul aksesibilitas -->
</head>
<body>
    {{-- Bagian Title --}}
    <header class="title">
        <h1>DASHBOARD</h1>
    </header>

    {{-- Bagian Monitoring --}}
    <div class="card">
        {{-- Temperature and Humidity Sensor (DHT11) --}}
        <div class="button-dht">
            <a class="button data" id="temp" href={{route('web.dht11')}}>
                <h3>Temperature</h3>
                <p><span id="temp_c">{{ $temp_c }}°C</span></p>
            </a>
            <a class="button data" href={{route('web.dht11')}}>
                <h3>Humidity</h3>
                <p><span id="humid_value">{{ $humid }}%</span></p>
            </a>
            <div class="gaugeMonitoring">
                <div id="temp-label" class="dynamic-label"></div>
                <div class="gaugeContainer">
                    <div class="gauge gaugeTemp"></div>
                    <div class="icon" id="gaugeTempIcon"></div>
                </div>
                <div class="gaugeContainer">
                    <div class="gauge gaugeHumidity"></div>
                    <div class="icon" id="gaugeHumidityIcon"></div>
                </div>
                <div id="humid-label" class="dynamic-label"></div>
            </div>
        </div>

        {{-- Raindrop Sensor --}}
        <a class="button" href={{route('web.rain')}} id="rain">
            <h3>Raindrop</h3>
            <p><span id="rain_value">{{ $rain_value }}</span></p>
        </a>

        {{-- Gas Sensor (MQ-2) --}}
        <a class="button" id="gas" href={{route('web.mq2')}}>
            <div class="info">
                <h3>Gas</h3>
                <p><span id="gas_value">{{ $gas_value }} ppm</span></p>
            </div>
            <div id="container-gauge"></div>
        </a>

        <script>
            $(document).ready(function() {
                var tempValue;
                var humidValue;

                function fetchLatestTempAndHumid() {
                    $.ajax({
                        url: '/latest-dht11',
                        method: 'GET',
                        success: function(data) {
                            tempValue = data.temp_c;
                            humidValue = data.humid;
                            $('#temp_c').text(data.temp_c + '°C');
                            $('#humid_value').text(data.humid + '%');

                            var tempPercentage = (data.temp_c / 100) * 100; // Assuming max temp is 100°C
                            var humidPercentage = data.humid; // Humidity is in percentage

                            var tempColor;
                            var tempLabel;
                            var tempIcon;
                            if (data.temp_c <= 25) {
                                tempColor = '#6488EA'; // Blue for cold
                                tempLabel = 'Suhu Dingin';
                                tempIcon = '🥶'; // Cold icon
                            } else if (data.temp_c <= 35) {
                                tempColor = '#6fc276'; // Green for normal
                                tempLabel = 'Suhu Normal';
                                tempIcon = '😌'; // Normal icon
                            } else if (data.temp_c <= 50) {
                                tempColor = '#ffe37a'; // Yellow for hot
                                tempLabel = 'Suhu Panas';
                                tempIcon = '🥵'; // Hot icon
                            } else {
                                tempColor = '#f94449'; // Red for very hot
                                tempLabel = 'MENYALA ABANGKU';
                                tempIcon = '💀'; // Very hot icon
                            }

                            var humidColor;
                            var humidLabel;
                            var humidIcon;
                            if (data.humid <= 25) {
                                humidColor = '#6488EA'; // Blue for low humidity
                                humidLabel = 'Kelembaban Rendah';
                                humidIcon = '😓'; // Low humidity icon
                            } else if (data.humid <= 50) {
                                humidColor = '#6fc276'; // Green for moderate humidity
                                humidLabel = 'Kelembaban Normal';
                                humidIcon = '😌'; // Moderate humidity icon
                            } else if (data.humid <= 75) {
                                humidColor = '#ffe37a'; // Yellow for high humidity
                                humidLabel = 'Kelembaban Tinggi';
                                humidIcon = '🥵'; // High humidity icon
                            } else {
                                humidColor = '#f94449'; // Red for very high humidity
                                humidLabel = 'Kelembaban Sangat Tinggi';
                                humidIcon = '💀'; // Very high humidity icon
                            }

                            $('#temp-label').text(tempLabel);
                            $('#humid-label').text(humidLabel);

                            $('#temp-label').css('color', tempColor).css('font-size', '15px');
                            $('#humid-label').css('color', humidColor).css('font-size', '15px');

                            $('.gaugeTemp').css('width', tempPercentage + '%').css('background-color', tempColor);
                            $('.gaugeHumidity').css('width', humidPercentage + '%').css('background-color', humidColor);

                            $('#gaugeTempIcon').text(tempIcon);
                            $('#gaugeHumidityIcon').text(humidIcon);

                            // Adjust icon position
                            $('#gaugeTempIcon').css('left', `calc(${tempPercentage}% - 35px)`);
                            $('#gaugeHumidityIcon').css('left', `calc(${humidPercentage}% - 35px)`);
                        },
                        error: function(error) {
                            console.log('Error fetching latest temperature and humidity:', error);
                        }
                    });
                }

                function fetchLatestRain() {
                    $.ajax({
                        url: '/latest-rain',
                        method: 'GET',
                        success: function(data) {
                            $('#rain_value').text(data.rain_value);
                        },
                        error: function(error) {
                            console.log('Error fetching latest rain data:', error);
                        }
                    });
                }

                // Variabel global untuk menyimpan waktu terakhir pesan dikirim
                var lastAlertTime = 0;
                var cooldownTime = 60000; // Waktu cooldown dalam milidetik (10 menit = 600000ms

                function fetchLatestMq2() {
                    $.ajax({
                        url: '/latest-mq2',
                        method: 'GET',
                        success: function(data) {
                            var gasValue = data.gas_value;
                            $('#gas_value').text(gasValue + ' ppm');

                            // Check if the gas value exceeds 1400
                            if (gasValue > 1400) {
                                sendWhatsAppAlert(gasValue, tempValue, humidValue);
                            }

                            console.log(data); // Debug: Log data dari server
                            if (data && typeof data.gas_value === 'string' && !isNaN(data.gas_value)) {
                                var gasValue = parseFloat(data.gas_value);
                                $('#gas_value').text(gasValue + ' ppm');
                                updateGauge(gasValue);
                            } else if (data && typeof data.gas_value === 'number') {
                                var gasValue = data.gas_value;
                                $('#gas_value').text(gasValue + ' ppm');
                                updateGauge(gasValue);
                            } else {
                                console.error('Invalid data format:', data);
                            }
                        },
                        error: function(error) {
                            console.log('Error fetching latest gas data:', error);
                        }
                    });
                }

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
                        max: 3700, // Sesuaikan nilai maksimum
                        stops: [
                            [0.1, '#6fc276'], // hijau
                            [0.5, '#ffe37a'], // kuning
                            [0.9, '#f94449'] // merah
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
                            text: 'Gas Level',
                            style: {
                            fontFamily: 'JetBrains Mono'
                        }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Gas Level',
                        data: [0],
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:25px">{y}</span><br/><span style="font-size:12px;opacity:0.4";font-family:"JetBrains Mono">ppm</span></div>'
                        },
                        tooltip: {
                            valueSuffix: ' ppm'
                        }
                    }]
                }));

                function updateGauge(gasValue) {
                    chart.series[0].points[0].update(gasValue);
                }

                function sendWhatsAppAlert(gasValue, tempValue, humidValue) {
                    var currentTime = new Date().getTime();

                    // Check if the cooldown period has passed
                    if (currentTime - lastAlertTime >= cooldownTime) {
                        var apiKey = 'n9NNqRF_PUbLf8v4TYzP'; // Replace with your Fonnte API key
                        var phoneNumber = '+6282299006083'; // Target phone number
                        var message = `🔥🔥🔥 MENYALA ABANGKU 🔥🔥🔥\n\nGas Concentration: ${gasValue} ppm\nTemperature: ${tempValue}°C\nHumidity: ${humidValue}%\n\nThe notification will appear again if conditions remain dangerous in the next 1 minutes.`;

                        $.ajax({
                            url: 'https://api.fonnte.com/send', // Fonnte API endpoint
                            method: 'POST',
                            headers: {
                                'Authorization': apiKey,
                                'Content-Type': 'application/x-www-form-urlencoded' // Ensure proper content type
                            },
                            data: {
                                'target': phoneNumber,
                                'message': message,
                                'countryCode': '62' // Country code for Indonesia
                            },
                            success: function(response) {
                                console.log('WhatsApp alert sent successfully:', response);
                                lastAlertTime = currentTime; // Update last alert time
                            },
                            error: function(error) {
                                console.log('Error sending WhatsApp alert:', error);
                                console.log('Error details:', error.responseText);
                            }
                        });
                    } else {
                        console.log('Cooldown active. Alert not sent.');
                    }
                }

                // Fetch the latest temperature and humidity every 1 second
                setInterval(fetchLatestTempAndHumid, 1000);

                // Fetch the latest rain data every 1 second
                setInterval(fetchLatestRain, 1000);

                // Fetch the latest rain data every 1 second
                setInterval(fetchLatestMq2, 1000);
            });
        </script>

        {{-- LED Control --}}
        <div class="led" href="" id="led">
            <h3>LED Control</h3>
            <div class="toggle">
                <button class="btnLed" onclick="toggleLED('red')" id="red">Red</button>
                <button class="btnLed" onclick="toggleLED('green')" id="green">Green</button>
                <button class="btnLed" onclick="toggleLED('blue')" id="blue">Blue</button>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>
                <script>
                    var broker = 'wss://a3de186b.ala.asia-southeast1.emqxsl.com:8084/mqtt'; // Alamat WebSocket broker MQTT Anda
                    var topicBase = 'esp32/led/';

                    var client = new Paho.MQTT.Client(broker, 'web_client_' + new Date().getTime());

                    client.onMessageArrived = function(message) {
                        console.log("onMessageArrived:"+message.payloadString);
                    }

                    client.onConnectionLost = function (responseObject) {
                        console.log('Connection lost: ' + responseObject.errorMessage);
                    };

                    function connectAndSendMessage(color, message) {
                        client.connect({
                            userName: 'mentoring', // Username
                            password: 'mentoring', // Password
                            useSSL: true,
                            onSuccess: function () {
                                console.log('Connected to MQTT broker');
                                var topic = topicBase + color;
                                var messageObj = new Paho.MQTT.Message(message);
                                messageObj.destinationName = topic;
                                client.send(messageObj);
                                console.log('Message sent:', message);
                                alert('Message sent successfully!');
                                client.disconnect();
                            },
                            onFailure: function (errorMessage) {
                                console.error('Failed to connect to MQTT broker:', errorMessage);
                                alert('Failed to send message. Please check MQTT connection.');
                            }
                        });
                    }

                    function toggleLED(color) {
                        var button = document.getElementById(color);
                        if (button) {
                            var isActive = button.classList.toggle('active');
                            var message = isActive ? 'on' : 'off';
                            connectAndSendMessage(color, message);
                        } else {
                            console.error('Button not found for color:', color);
                        }
                    }
                </script>
            </div>
        </div>

    {{-- Bagian Kaki --}}
    <footer class="footer">
        <h2>Created by Ahmaaad</h2>
    </footer>
</body>
</html>

