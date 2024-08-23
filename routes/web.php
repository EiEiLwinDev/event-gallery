<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); 

/** important path for vue router */
Route::any('/{any}', function () {
    return view('welcome');
})->where(['any' => '.*']);