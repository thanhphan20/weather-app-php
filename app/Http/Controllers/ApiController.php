<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function index()
    {
        return "hello world";
    }

    public function boot()
    {
        parent::boot();

        Validator::extend('special_characters', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[^@#$%&()0-9]+$/', $value);
        });
    }

    public function forecast(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'city' => ['required', 'string', 'regex:/^[^@#$%&()0-9]+$/'],
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Please enter a valid city. The city field must not contain special characters or numbers and not empty.');
            return view('dashboard', ['weatherData' => null]);
        }

        $city = $request->input('city');

        try {
            $response = Http::get('http://api.weatherapi.com/v1/forecast.json?', [
                'key' => config('services.weather_api.key'),
                'q' => $city,
                'days' => 14,
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();

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
                session()->flash('error', ApiException::httpError('An error occurred while fetching the forecast')->getMessage());
                return view('dashboard');
            }
        } catch (ApiException $e) {
            session()->flash('error', $e::httpError()->getMessage());
            return view('dashboard');
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

        $validator = Validator::make($request->all(), [
            'city' => ['required', 'string', 'regex:/^[^@#$%&()0-9]+$/'],
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Please enter a valid city. The city field must not contain special characters or numbers and not empty.');
            return view('dashboard', ['weatherData' => null]);
        }

        $city = $request->input('city');
        $date = $request->input('date');
        try {
            $response = Http::get('https://api.weatherapi.com/v1/history.json?', [
                'key' => config('services.weather_api.key'),
                'q' => $city,
                'dt' => $date,
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();

                return view('detail', ['weatherData' => $weatherData]);
            } else {
                session()->flash('error', ApiException::httpError('An error occurred while fetching history')->getMessage());
                return view('dashboard');
            }
        } catch (ApiException $e) {
            session()->flash('error', $e::httpError()->getMessage());
            return view('dashboard');
        }
    }

    public function detailFuture(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'city' => ['required', 'string', 'regex:/^[^@#$%&()0-9]+$/'],
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Please enter a valid city. The city field must not contain special characters or numbers and not empty.');
            return view('dashboard', ['weatherData' => null]);
        }
        
        $city = $request->input('city');
        $date = $request->input('date');
        try {
            $response = Http::get('https://api.weatherapi.com/v1/future.json?', [
                'key' => config('services.weather_api.key'),
                'q' => $city,
                'dt' => $date,
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();

                return view('detail', ['weatherData' => $weatherData]);
            } else {
                return view('dashboard', ['error-future' => 'An error occurred while fetching future for']);
            }
        } catch (ApiException $e) {
            session()->flash('error', $e::httpError()->getMessage());
            return view('dashboard');
        }
    }

    public function clearHistory()
    {
        session()->flush();
        return redirect()->route('dashboard');
    }
}
