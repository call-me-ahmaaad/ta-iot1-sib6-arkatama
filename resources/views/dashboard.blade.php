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
                <div class="gaugeContainer">
                    <div class="gauge gaugeTemp"></div>
                    <div class="gaugeLabel" id="gaugeTempLabel">0°C</div>
                    <div class="gaugeIcon" id="gaugeTempIcon">🔵</div> <!-- Icon for temperature gauge -->
                </div>
                <div class="gaugeContainer">
                    <div class="gauge gaugeHumidity"></div>
                    <div class="gaugeLabel" id="gaugeHumidityLabel">0%</div>
                    <div class="gaugeIcon" id="gaugeHumidityIcon">💧</div> <!-- Icon for humidity gauge -->
                </div>
            </div>
        </div>

        {{-- Raindrop Sensor --}}
        <a class="button" href="" id="rain">
            <h3>Raindrop</h3>
            <p><span id="rain_value">{{ $rain_value }}</span></p>
        </a>

        {{-- Gas Sensor (MQ-2) --}}
        <a class="button" id="gas" href="" id="gas">
            <h3>Gas</h3>
            <p>70 ppm</p>
        </a>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
    function fetchLatestTempAndHumid() {
        $.ajax({
            url: '/latest-dht11',
            method: 'GET',
            success: function(data) {
                $('#temp_c').text(data.temp_c + '°C');
                $('#humid_value').text(data.humid + '%');

                var tempPercentage = (data.temp_c / 100) * 100; // Assuming max temp is 100°C
                var humidPercentage = data.humid; // Humidity is in percentage

                var tempColor, tempIcon;
                if (data.temp_c <= 25) {
                    tempColor = '#6488EA'; // Blue for cold
                    tempIcon = '🥶'; // Cold face emoji
                } else if (data.temp_c <= 35) {
                    tempColor = '#6fc276'; // Green for normal
                    tempIcon = '😊'; // Happy face emoji
                } else if (data.temp_c <= 50) {
                    tempColor = '#ffe37a'; // Yellow for hot
                    tempIcon = '🥵'; // Sweat face emoji
                } else {
                    tempColor = '#f94449'; // Red for very hot
                    tempIcon = '💀'; // Hot face emoji
                }

                var humidColor, humidIcon;
                if (data.humid <= 25) {
                    humidColor = '#6488EA'; // Blue for low humidity
                    humidIcon = '🌵'; // Cold face emoji
                } else if (data.humid <= 50) {
                    humidColor = '#6fc276'; // Green for moderate humidity
                    humidIcon = '💦'; // Happy face emoji
                } else if (data.humid <= 75) {
                    humidColor = '#ffe37a'; // Yellow for high humidity
                    humidIcon = '🌊'; // Sweat face emoji
                } else {
                    humidColor = '#f94449'; // Red for very high humidity
                    humidIcon = '💀'; // Hot face emoji
                }

                $('.gaugeTemp').css('width', tempPercentage + '%').css('background-color', tempColor);
                $('.gaugeHumidity').css('width', humidPercentage + '%').css('background-color', humidColor);

                $('#gaugeTempLabel').text(data.temp_c + '°C');
                $('#gaugeHumidityLabel').text(data.humid + '%');

                // Adjust label position
                $('#gaugeTempLabel').css('left', `calc(${tempPercentage}% - 55px)`);
                $('#gaugeHumidityLabel').css('left', `calc(${humidPercentage}% - 35px)`);

                // Adjust icon position and set icon
                $('#gaugeTempIcon').css('left', `calc(${tempPercentage}% - 10px)`).text(tempIcon);
                $('#gaugeHumidityIcon').css('left', `calc(${humidPercentage}% - 10px)`).text(humidIcon);
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

    // Fetch the latest temperature and humidity every 1 second
    setInterval(fetchLatestTempAndHumid, 1000);

    // Fetch the latest rain data every 1 second
    setInterval(fetchLatestRain, 1000);
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

