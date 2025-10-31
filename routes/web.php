<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $category = \Wsmallnews\Category\Models\CategoryType::query()
        ->scopeable('aaa', 666)
        ->firstOrCreate(['level' => 5]);

    dd($category);

    return view('welcome');
});
