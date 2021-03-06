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

Route::get('/', function () {
    return view('cadastrar');
});
Route::get('/cadastrar', function () {
    return view('cadastrar');
});
Route::post('/register', 'Calendar@store');

Route::get('/listar', 'Calendar@list');
Route::get('/calendar/selectEvents', 'Calendar@selectEvents');
Route::get('/calendar/getEventsInDate/{date}', 'Calendar@getEventsInDate')
    ->middleware('ajax');

Route::get('/nonOperatingDays', 'NonOperatingDay@nonOperatingDays');
Route::get('/calendar/getNonOperatingDays', 'NonOperatingDay@getNonOperatingDays');
Route::delete('/calendar/deleteNonOperatingDay/{id}', 'NonOperatingDay@deleteNonOperatingDay')
    ->middleware('ajax');
Route::post('/calendar/addNonOperatingDay', 'NonOperatingDay@addNonOperatingDay')
    ->middleware('ajax');