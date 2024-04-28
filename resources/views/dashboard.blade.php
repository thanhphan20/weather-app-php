@extends('layouts.app')
@section('title', 'dashboard')
@section('content')

    <h1>Weather Dashboard</h1>
    @if (session('sub'))
        <div class="alert container alert-success">
            {{ session('sub') }}
        </div>
    @elseif (session('unsub'))
        <div class="alert container alert-success">
            {{ session('unsub') }}
        </div>
    @elseif (session('confirmed'))
        <div class="alert container alert-success">
            {{ session('confirmed') }}
        </div>
    @elseif (session('already'))
        <div class="alert container alert-success">
            {{ session('already') }}
        </div>
    @elseif (session('error-sub'))
        <div class="alert container alert-danger">
            {{ session('error-sub') }}
        </div>
    @elseif (session('error-un'))
        <div class="alert container alert-danger">
            {{ session('error-un') }}
        </div>
    @elseif (session('error-confirmed'))
        <div class="alert container alert-danger">
            {{ session('error-confirmed') }}
        </div>
    @elseif (session('error-unsubscribed'))
        <div class="alert container alert-danger">
            {{ session('error-unsubscribed') }}
        </div>
    @endif
    <div class="container">
        <div class="weather-input">
            <h3>Enter a City Name</h3>
            <form method="get" id="search-form" action="/forecast">
                <input class="city-input" type="text" name="city" placeholder="E.g., New York, London, Tokyo">
                <button type="submit" class="search-btn">Search</button>
            </form>
            <div class="separator"></div>
            <button class="location-btn">Use Current Location</button>
            <div class="margin"></div>
            <h3>Subscribe to receive daily weather forecast</h3>
            <input class="email-input" id="email" type="email" placeholder="E.g., youremailhere@gmail.com" />
            <button id="subscribe-btn" class="subscribe-btn">Register</button>
            <button id="unsubscribe-btn" class="unsubscribe-btn">Unsubscribe</button>

        </div>

        @if (isset($weatherData))
            <div class="weather-data">
                <div class="current-weather">
                    <div class="details">
                        <h2>{{ $weatherData['location']['name'] }} ( {{ $weatherData['location']['localtime'] }} )</h2>
                        <h6>Temperature: {{ $weatherData['current']['temp_c'] }}°C</h6>
                        <h6>Wind: {{ $weatherData['current']['wind_mph'] }}M/S</h6>
                        <h6>Humidity: {{ $weatherData['current']['humidity'] }}%</h6>
                    </div>
                    <div class="icon">
                        <img src={{ $weatherData['current']['condition']['icon'] }} alt="icon" />
                    </div>
                </div>
                <div class="days-forecast">
                    <h2 id="number-days">4-Day Forecast</h2>
                    <ul class="weather-cards">
                        @foreach ($weatherData['forecast']['forecastday'] as $key => $item)
                            @if ($key < 5)
                                <li class="card">
                                    <h3>( {{ $item['date'] }} )</h3>
                                    <div class="icon">
                                        <img src={{ $item['day']['condition']['icon'] }} alt="icon" />
                                    </div>
                                    <h6>Temp: {{ $item['day']['avgtemp_c'] }}C</h6>
                                    <h6>Wind: {{ $item['day']['maxwind_kph'] }} M/S</h6>
                                    <h6>Humidity: {{ $item['day']['avghumidity'] }}%</h6>
                                </li>
                            @else
                                <li class="card" style="display: none;">
                                    <h3>( {{ $item['date'] }} )</h3>
                                    <div class="icon">
                                        <img src={{ $item['day']['condition']['icon'] }} alt="icon" />
                                    </div>
                                    <h6>Temp: {{ $item['day']['avgtemp_c'] }}C</h6>
                                    <h6>Wind: {{ $item['day']['maxwind_kph'] }} M/S</h6>
                                    <h6>Humidity: {{ $item['day']['avghumidity'] }}%</h6>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="margin"></div>
                    <button class="load-btn" id="load-more-btn">Load More</button>
                </div>
                @if (isset($history))
                    <div class="history-data">
                        @foreach ($history as $item)
                            <div class="current-weather">
                                <div class="details">
                                    <h2>{{ $item['location']['name'] }} ( {{ $item['location']['localtime'] }} )
                                    </h2>
                                    <h6>Temperature: {{ $item['current']['temp_c'] }}°C</h6>
                                    <h6>Wind: {{ $item['current']['wind_mph'] }}M/S</h6>
                                    <h6>Humidity: {{ $item['current']['humidity'] }}%</h6>
                                </div>
                                <div class="icon">
                                    <img src={{ $item['current']['condition']['icon'] }} alt="icon" />
                                </div>
                            </div>
                            <div class="margin"></div>
                        @endforeach
                        <a href="{{ route('clear.history') }}" class="btn btn-danger load-btn">Clear History</a>
                    </div>
                @endif
                <div class="past-future">
                    <label for="date-input">Select the day to get weather forecast data</label>
                    
                    <input type="text" class="form-control" id="date-input" name="date" placeholder="Select Day">
                    <div class="margin"></div>
                    <button id="past-btn" class="btn btn-primary load-btn">Get past weather data</button>
                    <button id="future-btn" class="btn btn-primary load-btn">Get future weather data</button>

                </div>
            </div>
        @else
            <div class="weather-data">
                <div class="current-weather">
                    <div class="details">
                        <h2>_______ ( ______ )</h2>
                        <h6>Temperature: __°C</h6>
                        <h6>Wind: __ M/S</h6>
                        <h6>Humidity: __%</h6>
                    </div>
                </div>
                <div class="days-forecast">
                    <h2>5-Day Forecast</h2>
                    <ul class="weather-cards">
                        <li class="card">
                            <h3>( ______ )</h3>
                            <h6>Temp: __C</h6>
                            <h6>Wind: __ M/S</h6>
                            <h6>Humidity: __%</h6>
                        </li>
                        <li class="card">
                            <h3>( ______ )</h3>
                            <h6>Temp: __C</h6>
                            <h6>Wind: __ M/S</h6>
                            <h6>Humidity: __%</h6>
                        </li>
                        <li class="card">
                            <h3>( ______ )</h3>
                            <h6>Temp: __C</h6>
                            <h6>Wind: __ M/S</h6>
                            <h6>Humidity: __%</h6>
                        </li>
                        <li class="card">
                            <h3>( ______ )</h3>
                            <h6>Temp: __C</h6>
                            <h6>Wind: __ M/S</h6>
                            <h6>Humidity: __%</h6>
                        </li>
                        <li class="card">
                            <h3>( ______ )</h3>
                            <h6>Temp: __C</h6>
                            <h6>Wind: __ M/S</h6>
                            <h6>Humidity: __%</h6>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
