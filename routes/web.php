<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

/* API */
Route::get('/forecast', 'App\Http\Controllers\ApiController@forecast')->name('weather.forecast');
Route::get('/past', 'App\Http\Controllers\ApiController@detailPast')->name('weather.past');
Route::get('/future', 'App\Http\Controllers\ApiController@detailFuture')->name('weather.future');
Route::get('/hello', 'App\Http\Controllers\ApiController@index');
Route::get('/clear-history', 'App\Http\Controllers\ApiController@clearHistory')->name('clear.history');

/* Subscription */
Route::post('/subscribe', 'App\Http\Controllers\SubscriptionController@subscribe');
Route::post('/unsubscribe', 'App\Http\Controllers\SubscriptionController@unsubscribe');
Route::get('/confirm-subscription', 'App\Http\Controllers\SubscriptionController@confirmSubscription')->name('confirm.subscription');
Route::get('/confirm-unsubscription', 'App\Http\Controllers\SubscriptionController@confirmUnsubscription')->name('confirm.unsubscription');