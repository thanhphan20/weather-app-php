@extends('layouts.app')
@section('title', 'detail')
@section('content')

    <h1>Weather Detail</h1>
    <div class="container">
        <div class="weather-data">
            <div class="current-weather">
                <div class="details">
                    <h2>{{ $weatherData['location']['name'] }} ( {{ $weatherData['forecast']['forecastday'][0]['date'] }} )
                    </h2>
                    <h6>Temperature: {{ $weatherData['forecast']['forecastday'][0]['day']['avgtemp_c'] }}°C</h6>
                    <h6>Wind: {{ $weatherData['forecast']['forecastday'][0]['day']['maxwind_mph'] }}M/S</h6>
                    <h6>Humidity: {{ $weatherData['forecast']['forecastday'][0]['day']['avghumidity'] }}%</h6>
                    <h6>UV: {{ $weatherData['forecast']['forecastday'][0]['day']['uv'] }}</h6>
                </div>
                <div class="second-details">
                    <h6>Sunrise: {{ $weatherData['forecast']['forecastday'][0]['astro']['sunrise'] }}</h6>
                    <h6>Sunset: {{ $weatherData['forecast']['forecastday'][0]['astro']['sunset'] }}</h6>
                    <h6>Moonrise: {{ $weatherData['forecast']['forecastday'][0]['astro']['moonrise'] }}</h6>
                    <h6>Moonset: {{ $weatherData['forecast']['forecastday'][0]['astro']['moonset'] }}</h6>
                    <h6>Moon Phase: {{ $weatherData['forecast']['forecastday'][0]['astro']['moon_phase'] }}</h6>
                    <h6>Moon Illumination: {{ $weatherData['forecast']['forecastday'][0]['astro']['moon_illumination'] }}
                    </h6>
                </div>
                <div class="icon">
                    <img src={{ $weatherData['forecast']['forecastday'][0]['day']['condition']['icon'] }} alt="icon" />
                </div>
            </div>
            <ul class="weather-cards-hours">
                @foreach ($weatherData['forecast']['forecastday'][0]['hour'] as $key => $item)
                    <li class="card-hour" style="{{ $item['is_day'] != 0 ? 'background:#5372F0' : '' }}">
                        <h3>( {{ $item['time'] }} )</h3>
                        <div class="icon">
                            <img src={{ $item['condition']['icon'] }} alt="icon" />
                        </div>
                        <h6>Temp: {{ $item['temp_c'] }}C</h6>
                        <h6>Wind: {{ $item['wind_mph'] }} M/S</h6>
                        <h6>Humidity: {{ $item['humidity'] }}%</h6>
                        <h6>Cloud: {{ $item['cloud'] }}</h6>
                        <h6>UV: {{ $item['uv'] }}</h6>
                        <h6>Chance of rain: {{ $item['chance_of_rain'] }} M/S</h6>
                        <h6>Chance of snow: {{ $item['chance_of_snow'] }} M/S</h6>
                    </li>
                @endforeach
            </ul>
        </div>
