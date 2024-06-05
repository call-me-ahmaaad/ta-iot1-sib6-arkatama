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
            <p id="unit">Quantity in day:</p>
            <p><span id="quantity_value">0 times</span></p>
        </div>

        <div class="card" id="duration">
            <p id="unit">Total Duration:</p>
            <p><span id="duration_value">0 hours</span></p>
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

                function fetchLatestRain() {
                    $.ajax({
                        url: '/latest-rain',
                        method: 'GET',
                        success: function(data) {
                            resetDataIfNewDay();

                            const currentRainValue = data.rain_value;

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
        <script>
            let rainChart;
            const baseUrl = '{{ url('/') }}';
            let lastTimestamp = null;

            async function requestData() {
                let endpoint = `${baseUrl}/latest-rain`;

                try {
                    const result = await fetch(endpoint, {
                        method: 'GET',
                    });
                    if (result.ok) {
                        const data = await result.json();
                        console.log('Fetched data:', data);  // Debugging: log fetched data

                        if (data && data.rain_value !== null && data.created_at !== null) {
                            let timestamp = new Date(data.created_at).getTime();
                            let value = Number(data.rain_value);

                            // Check if the data is new based on created_at
                            if (timestamp !== lastTimestamp) {
                                lastTimestamp = timestamp;

                                console.log(`Rain Timestamp: ${timestamp}, Value: ${value}`); // Debugging: log each point

                                // Update gas data to chart
                                rainChart.series[0].addPoint([timestamp, value], true, rainChart.series[0].data.length > 20);
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
                rainChart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'rain_chart',
                        type: 'spline',
                        events: {
                            load: requestData
                        }
                    },
                    title: {
                        text: 'Rainsensor Detection',
                        style: {
                            color: '#f94449',
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
                        min: 0,
                        max: 2,
                        title: {
                            text: 'Rain Value',
                            margin: 80,
                            style: {
                                color: '#f94449'
                            }
                        }
                    },
                    series: [{
                        name: 'Rain Value',
                        data: [],
                        color: '#f94449'
                    }]
                });
            });
        </script>
    </div>
@endsection
