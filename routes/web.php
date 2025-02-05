<?php

use App\Livewire\Repeater;
use Illuminate\Support\Facades\Route;

use Money\Currency;
use Money\Money;

Route::get('/', function () {

    // $jimPrice = $hannahPrice = Money::EUR(2500);

    // $coupon = Money::EUR(500);
    // $finalPrice = $jimPrice->subtract($coupon);          // jimPrice - $coupon ，获得新的价格
    // dd($finalPrice);


    $jimPrice = $hannahPrice = Money::EUR(2500);
    $coupon = Money::EUR(500);

    $jimPrice = $jimPrice->subtract($coupon);           // 需要重新赋值
    $jimPrice->lessThan($hannahPrice); // true          // jimPrice 是否小于 $hannahPrice

    $jimPrice->equals(Money::EUR(2000)); // true      jimPrice 是否等于  Money::EUR(2000



    $value1 = Money::EUR(800);       // €8.00
    $value2 = Money::EUR(500);       // €5.00

    $result = $value1->add($value2); // €13.00
    echo $result;


    return view('welcome');
});


Route::get('repeater', Repeater::class);