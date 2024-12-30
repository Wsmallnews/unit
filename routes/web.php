<?php

use App\Livewire\Repeater;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('repeater', Repeater::class);