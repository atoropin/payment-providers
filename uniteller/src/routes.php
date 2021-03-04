<?php

use Illuminate\Support\Facades\Route;

Route::post('callback', 'UnitellerController@callback');
Route::get('success', 'UnitellerController@success');
Route::get('failed', 'UnitellerController@failed');
