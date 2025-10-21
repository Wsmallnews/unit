<?php

use App\Http\Controllers\PayController;
use App\Http\Controllers\TestController;
use App\Livewire\Repeater;
use App\Livewire\SkusWidget;
use Illuminate\Support\Facades\Route;

use App\Livewire\DeepSeek;
use App\Livewire\TestImage;
use Money\Currency;
use Money\Money;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;


Route::get('/pay', PayController::class . '@pay');
Route::get('/money', TestController::class . '@money');
Route::get('/notify/{payment}', PayController::class . '@notify');

Route::get('/deepseek', DeepSeek::class);
Route::get('/test-image', TestImage::class);
Route::get('/skus-widget', SkusWidget::class);


Route::get('/', function () {

    $a = '# PHP 冒泡排序实现

冒泡排序是一种简单的排序算法，它重复地遍历要排序的列表，比较相邻的元素并交换它们的位置，直到列表排序完成。

以下是使用 PHP 实现的冒泡排序算法：

```php
<?php
function bubbleSort(array $array): array {
    $length = count($array);
    
    // 外层循环控制排序轮数
    for ($i = ; $i < $length - 1; $i++) {
        // 内层循环控制每轮比较次数
        for ($j = ; $j < $length - 1 - $i; $j++) {
            // 如果前一个元素大于后一个元素，则交换它们
            if ($array[$j] > $array[$j + 1]) {
                // 交换元素
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
    
    return $array;
}

// 测试示例
$unsortedArray = [64, 34, 25, 12, 22, 11, 90];
$sortedArray = bubbleSort($unsortedArray);

echo "排序前: " . implode(", ", $unsortedArray) . "\n";
echo "排序后: " . implode(", ", $sortedArray) . "\n";
?>
```

## 优化版本

我们可以对冒泡排序进行一些优化，比如在一轮比较中没有发生交换时提前结束排序：

```php
<?php
function optimizedBubbleSort(array $array): array {
    $length = count($array);
    
    for ($i = ; $i < $length - 1; $i++) {
        $swapped = false;
        
        for ($j = ; $j < $length - 1 - $i; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                // 交换元素
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
                $swapped = true;
            }
        }
        
        // 如果本轮没有发生交换，说明数组已经有序
        if (!$swapped) {
            break;
        }
    }
    
    return $array;
}
?>
```

## 时间复杂度

- 最坏情况时间复杂度：O(n²) - 当数组是逆序时
- 最好情况时间复杂度：O(n) - 当数组已经有序时（优化版本）
- 平均时间复杂度：O(n²)

冒泡排序是一种稳定的排序算法，适合小规模数据的排序。  ';

    $this->answer = app(\Spatie\LaravelMarkdown\MarkdownRenderer::class)->toHtml($a);

    // dd($this->answer);
    echo $this->answer;

    exit;


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