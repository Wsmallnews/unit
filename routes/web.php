<?php

use App\Livewire\Repeater;
use Illuminate\Support\Facades\Route;

use Money\Currency;
use Money\Money;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;

Route::get('/', function () {

    echo \Illuminate\Support\Number::currency(120321);exit;

    // $jimPrice = $hannahPrice = Money::EUR(2500);

    // $coupon = Money::EUR(500);
    // $finalPrice = $jimPrice->subtract($coupon);          // jimPrice - $coupon ，获得新的价格
    // dd($finalPrice);

    $formater = function ($locale = null) {
        $locale = $locale ?? config('app.locale');      // 地区
        $currency = 'EUR';                              // 货币符号

        $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $intlFormatter = new IntlLocalizedDecimalFormatter($numberFormatter, new ISOCurrencies());

        $intlFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

        $bitcoinFormatter = new BitcoinMoneyFormatter(7, new BitcoinCurrencies());

        $moneyFormatter = new AggregateMoneyFormatter([
            $currency => $intlFormatter,
            'XBT' => $bitcoinFormatter,
        ]);

        return $moneyFormatter;
    };

    echo $formater()->format(Money::EUR(100000001)); // outputs ¥1.00
    exit;

    $dollars = new Money(100000001, new Currency('USD'));

    $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
    
    $intlFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
    

    $bitcoin = new Money(101, new Currency('XBT'));
    $bitcoinFormatter = new BitcoinMoneyFormatter(7, new BitcoinCurrencies());


    $moneyFormatter = new AggregateMoneyFormatter([
        'USD' => $intlFormatter,
        'XBT' => $bitcoinFormatter,
    ]);

    echo $moneyFormatter->format($dollars); // outputs $1.00
    echo $moneyFormatter->format($bitcoin); // outputs Ƀ0.0000010

exit;



    $jimPrice = $hannahPrice = Money::EUR(2500);
    $coupon = Money::EUR(500);

    dd($coupon);


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