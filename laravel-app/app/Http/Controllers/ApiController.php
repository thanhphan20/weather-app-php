<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    public function index()
    {
        return "hello world";
    }

    public function forecast(Request $request)
    {
        $city = $request->input('city');

        if (empty($city)) {
            return view('dashboard', ['weatherData' => null]);
        }

        $response = Http::get('http://api.weatherapi.com/v1/forecast.json?', [
            'key' => config('services.weather_api.key'),
            'q' => $city,
            'days' => 14,
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();

            // $request->session()->put('historyData:' . $city, $weatherData);
            // $this->saveWeatherHistory($request, $weatherData);
            // $history = $this->getWeatherHistory($request);

            $sessionKey = 'historyData:' . $city;
            $existingData = $request->session()->get($sessionKey);

            if (
                !$existingData ||
                $existingData['location']['name'] !== $weatherData['location']['name'] ||
                $existingData['location']['localtime'] !== $weatherData['location']['localtime']
            ) {
                $request->session()->put($sessionKey, $weatherData);

                $this->saveWeatherHistory($request, $weatherData);
            }

            $history = $this->getWeatherHistory($request);

            return view('dashboard', ['weatherData' => $weatherData,  'history' => $history]);
        } else {
            return view('dashboard', ['error' => 'An error occurred while fetching forecast']);
        }
    }

    private function saveWeatherHistory(Request $request, $weatherData)
    {
        $history = $this->getWeatherHistory($request);
        $history[] = $weatherData;
        $request->session()->put('weatherHistory', $history);
    }

    private function getWeatherHistory(Request $request)
    {
        $historyData = $request->session()->get('weatherHistory', []);
        return $historyData;
    }

    public function detailPast(Request $request)
    {

        $city = $request->input('city');
        $date = $request->input('date');

        $response = Http::get('https://api.weatherapi.com/v1/history.json?', [
            'key' => config('services.weather_api.key'),
            'q' => $city,
            'dt' => $date,
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();

            return view('detail', ['weatherData' => $weatherData]);
        } else {
            return view('dashboard', ['error-past' => 'An error occurred while fetching history']);
        }
    }

    public function detailFuture(Request $request)
    {

        $city = $request->input('city');
        $date = $request->input('date');

        $response = Http::get('https://api.weatherapi.com/v1/future.json?', [
            'key' => config('services.weather_api.key'),
            'q' => $city,
            'dt' => $date,
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();

            return view('detail', ['weatherData' => $weatherData]);
        } else {
            return view('dashboard', ['error-past' => 'An error occurred while fetching history']);
        }
    }

    public function clearHistory()
    {
        session()->flush();
        return redirect()->route('dashboard');
    }
}
