@extends('frontend.layouts.app')
@section('title')
{{ app_name() }} - Emergency Response Service Management System.
@endsection
@section('content')
<section class="bg-white dark:bg-gray-800">
   <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
      <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-600 dark:text-white sm:text-6xl">
         Public SOS
      </h1>
      <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-white sm:px-16 sm:text-2xl xl:px-48">
         Emergency Response Service Management System
      </h2>
      @include('frontend.includes.messages')
   </div>
</section>
<section class="bg-white dark:bg-gray-900">
   <div class="px-4 py-8 mx-auto max-w-screen-xl lg:py-16">
      <div class="grid gap-8 items-center mb-8 lg:mb-24 lg:grid-cols-12 lg:gap-12">
         <div class="col-span-6 text-center sm:mb-6 lg:mb-0 lg:text-left">
            <h1
               class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white md:text-5xl xl:text-6xl">
               <span class="block xl:inline">Public Safety</span>
               <span class="block xl:inline">SOS</span>
            </h1>
            <p class="mx-auto mb-6 max-w-xl text-gray-500 dark:text-gray-400 md:text-lg lg:mx-0 xl:mb-8 xl:text-xl">
            </p>
         </div>
         <div class="col-span-6">
            <iframe class="mx-auto w-full max-w-xl h-64 rounded-lg sm:h-96" width="560" height="315"
               src="https://www.youtube.com/embed/IVdxG3b0hpg?si=E39RpPARmrA-DoCv" title="YouTube video player"
               frameborder="0"
               allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
               referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
         </div>
      </div>
   </div>
</section>
<section class="bg-white dark:bg-gray-800">
   <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
      <!-- Executive Summary -->
      <div class="mb-12">
         <h2 class="mb-4 text-4xl font-bold text-gray-600 dark:text-white">I. Executive Summary</h2>
         <div class="max-w-none text-3xl text-gray-600 prose dark:text-white dark:prose-invert">
            <p>This comprehensive application delivers a strategic and operational framework for your incident
               management team, ensuring strict adherence to Public Safety and rescue service guidelines.
            </p>
            <br />
            <p>Leveraging the power of integrated Incident Command System (ICS) protocols, seamless inter-agency
               collaboration, and cutting-edge technology, this solution guarantees efficient and secure emergency
               response operations.
            </p>
            <br />
            <p>Our platform addresses critical areas including proactive preparedness, streamlined incident
               management, rigorous safety protocols, and continuous improvement through targeted training and
               dynamic policy development. Empower your team with the tools they need to excel.
            </p>
         </div>
      </div>
   </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=46.52&longitude=27.64&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m";

fetch(apiUrl)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const times = data.hourly.time.map(timeStr => new Date(timeStr));
        const temperatures = data.hourly.temperature_2m;
        const windSpeeds = data.hourly.wind_speed_10m;
        const humidity = data.hourly.relative_humidity_2m;

        const ctx = document.getElementById('weatherChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: times,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: temperatures,
                    borderColor: 'red',
                    fill: false
                }, {
                    label: 'Wind Speed (m/s)',
                    data: windSpeeds,
                    borderColor: 'blue',
                    fill: false
                },
                {
                    label: 'Relative Humidity (%)',
                    data: humidity,
                    borderColor: 'green',
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour'
                        }
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error("Error fetching or processing data:", error);
    });
</script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endsection
