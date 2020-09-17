<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/cadastrar', function () {
    return view('cadastrar');
});
Route::post('/register', 'Calendar@store');

Route::get('/list', 'Calendar@list');
Route::get('/calendar/selectEvents', 'Calendar@selectEvents');
Route::get('/calendar/getEventsInDate/{date}', 'Calendar@getEventsInDate')
    ->middleware('ajax');

Route::get('/nonOperatingDays', 'Calendar@nonOperatingDays');
Route::get('/calendar/getNonOperatingDays', 'Calendar@getNonOperatingDays');
Route::delete('/calendar/deleteNonOperatingDay/{id}', 'Calendar@deleteNonOperatingDay')
    ->middleware('ajax');
Route::post('/calendar/addNonOperatingDay', 'Calendar@addNonOperatingDay')
    ->middleware('ajax');
