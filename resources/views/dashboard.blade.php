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
            <a class="button data" id="temp" href="#">
                <h3>Temperature</h3>
                <p>70°C</p>
            </a>
            <a class="button data" id="humid" href="">
                <h3>Humidity</h3>
                <p>70%</p>
            </a>
        </div>

        {{-- Raindrop Sensor --}}
        <a class="button" href="" id="rain">
            <h3>Raindrop</h3>
            <p>ON</p>
        </a>

        {{-- Gas Sensor (MQ-2) --}}
        <a class="button" id="gas" href="" id="gas">
            <h3>Gas</h3>
            <p>70 ppm</p>
        </a>

        {{-- LED Control --}}
        <div class="led" href="" id="led">
            <h3>LED Control</h3>
            <div class="toggle">
                <button class="btnLed" onclick="toggleLED(this, 'red')" id="red">Red</button>
                <button class="btnLed" onclick="toggleLED(this, 'green')" id="green">Green</button>
                <button class="btnLed" onclick="toggleLED(this, 'blue')" id="blue">Blue</button>

                <script>
                    function toggleLED(button, color) {
                        var isActive = button.classList.toggle('active');
                        var action = isActive ? 'on' : 'off';
                        var url = 'https://192.168.0.15/led/' + color + '/' + action;
                        fetch(url);
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

