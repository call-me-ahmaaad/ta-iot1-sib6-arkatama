@extends('layouts.sensorPage')
@section('css')
    <link rel="stylesheet" href={{URL::asset("/css/sensorPageRain.css")}}>
@endsection
@section('navbar')

@endsection
@section('container')
    <div class="label">
        <div class="card" id="rain">
            <p id="unit">Rain Value:</p>
            <p><span id="rain_value">{{ $rain_value }}</span></p>
        </div>

        <div class="card" id="quantity">
            <p id="unit">Quantity:</p>
            <p><span id="quantity_value">0</span></p>
        </div>

        <div class="card" id="duration">
            <p id="unit">Duration:</p>
            <p><span id="duration_value">0</span></p>
        </div>

        <script>
            $(document).ready(function() {
                let previousRainValue = 0;
                let rainQuantity = 0;
                let totalRainDuration = 0; // Total durasi hujan dalam satu hari (dalam jam)
                let startTime = null;
                let lastResetDate = new Date().toLocaleDateString();

                // Fungsi untuk menyimpan data ke localStorage
                function saveToLocalStorage() {
                    localStorage.setItem('rainQuantity', rainQuantity);
                    localStorage.setItem('lastResetDate', lastResetDate);
                    localStorage.setItem('totalRainDuration', totalRainDuration);
                }

                // Fungsi untuk mengambil data dari localStorage
                function loadFromLocalStorage() {
                    const savedQuantity = localStorage.getItem('rainQuantity');
                    const savedResetDate = localStorage.getItem('lastResetDate');
                    const savedDuration = localStorage.getItem('totalRainDuration');

                    if (savedQuantity !== null) {
                        rainQuantity = parseInt(savedQuantity, 10);
                        $('#quantity_value').text(rainQuantity);
                    }

                    if (savedResetDate !== null) {
                        lastResetDate = savedResetDate;
                    }

                    if (savedDuration !== null) {
                        totalRainDuration = parseFloat(savedDuration);
                        $('#duration_value').text(totalRainDuration.toFixed(2));
                    }
                }

                // Muat data dari localStorage saat halaman dimuat
                loadFromLocalStorage();

                function resetDataIfNewDay() {
                    let currentDate = new Date().toLocaleDateString();
                    if (currentDate !== lastResetDate) {
                        rainQuantity = 0;
                        totalRainDuration = 0;
                        lastResetDate = currentDate;
                        $('#quantity_value').text(rainQuantity);
                        $('#duration_value').text(totalRainDuration.toFixed(2));
                        saveToLocalStorage(); // Simpan perubahan ke localStorage
                    }
                }

                // Inisialisasi grafik Highcharts
                const chart = Highcharts.chart('rain_chart', {
                    chart: {
                        type: 'area'  // Mengubah tipe grafik menjadi area
                    },
                    title: {
                        text: 'Rain Value Over Time',
                        style: {
                                color: '#f94449'
                            }
                    },
                    xAxis: {
                        type: 'datetime',
                        tickPixelInterval: 150,
                        maxZoom: 20 * 1000,
                    },
                    yAxis: {
                        title: {
                            text: 'Rain Value',
                            style: {
                                color: '#f94449'
                            }
                        },
                        min: 0,
                        max: 2,
                    },
                    series: [{
                        name: 'Rain Value',
                        data: [],
                        color: '#f94449',
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                x2: 0,
                                y1: 0,
                                y2: 1
                            },
                            stops: [
                                [0, '#f94449'],
                                [1, 'rgba(0, 0, 0, 0)']
                            ]
                        }
                    }]
                });

                function fetchLatestRain() {
                    $.ajax({
                        url: '/latest-rain',
                        method: 'GET',
                        success: function(data) {
                            resetDataIfNewDay();

                            if (data && data.rain_value !== null && data.created_at !== null) {
                                const currentRainValue = data.rain_value;
                                const currentTime = new Date(data.created_at).getTime();

                                // Jika rain_value berubah dari 0 ke 1, catat waktu mulai
                                if (previousRainValue == 0 && currentRainValue == 1) {
                                    startTime = new Date();
                                }

                                // Jika rain_value berubah dari 1 ke 0, hitung periode hujan
                                if (previousRainValue == 1 && currentRainValue == 0) {
                                    if (startTime !== null) {
                                        let endTime = new Date();
                                        // Hitung durasi hujan dalam jam
                                        let duration = (endTime - startTime) / (1000 * 60 * 60);
                                        totalRainDuration += duration;
                                        rainQuantity += 1; // Tambahkan satu kejadian hujan
                                        startTime = null; // reset waktu mulai
                                        saveToLocalStorage(); // Simpan perubahan ke localStorage
                                    }
                                }

                                // Perbarui nilai rain_value sebelumnya
                                previousRainValue = currentRainValue;

                                // Update rain_value di halaman
                                $('#rain_value').text(currentRainValue);

                                // Update kuantitas di halaman
                                $('#quantity_value').text(rainQuantity + ' times');

                                // Update total durasi di halaman
                                $('#duration_value').text(totalRainDuration.toFixed(2) + ' hours');

                                // Tambahkan data ke grafik
                                chart.series[0].addPoint([currentTime, currentRainValue]);

                            }
                        },
                        error: function(error) {
                            console.log('Error fetching latest rain data:', error);
                        }
                    });
                }

                // Fetch the latest data every 3 seconds
                setInterval(fetchLatestRain, 3000);
            });
        </script>
    </div>
    <div class="table">
        <div id="rain_chart"></div>
    </div>
@endsection
