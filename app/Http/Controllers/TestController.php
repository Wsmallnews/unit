<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Wsmallnews\Order\Models\Order;
use Yansongda\Pay\Pay as YansongdaPay;
use Cknow\Money\Money;
use Wsmallnews\Support\Features\Currency;
use Wsmallnews\Product\Models\SkuPrice;

class TestController extends Controller
{


    
    public function money(Request $request)
    {
        // 测试内容
        // 1、数据库字段是否可以直接操作                                        // 可以直接进行运算 加减乘除 
        // 2、小数点位数为 3，或者 1 数据库中是如何存的                          // 比如数据库存的是 8001，则 IQD 3 位小数的货币取出来的具体就是 IQD 8 (IQD 格式不知道为啥默认不显示小数)； decimal 就是 8.001
        // 3、一个 Cknow\Money\Money 和 一个 12.34 如何互相操作
        // 4、如何在用户端显示出 拆分的 价格格式，符号 和 金额 分开               // 写个组件，根据 配置，决定显示方式，里面就处理 Cknow\Money\Money
        // 5、直接把 model 转 json ，price 是 {"amount":"1123331232","currency":"USD","formatted":"US$11,233,312.32"}
        // 6、api 的话就直接 在 Collection 和 Resources 中处理                  
        // 7、后台编辑时，如何正确展示价格      ->formatByDecimal()     formatStateUsing 还不行，state 被转数组了, 可以注入 model
        // 8、json 字段中的 价格 可以用 parse 格式化为 Cknow\Money\Money
        // 9、

        // 接下来
        // 接着改 ProductCalc 下面的方法  radar 实时的数据中，可以直接保存 原始结果，比如 Cknow\Money\Money, 最终存库时再做转换


        // $a = Money::USD(500)->formatByCurrencySymbol(); // $5.00
        // dd($a);


        echo sn_currency()->getSymbol();exit;


        // formatByDecimal 可以格式化为 字符串的 带小数的金额，不足的会补 0 ，比如 5.10
        // $ac = Money::parseByDecimal(1000, 'USD');
        // $acd = Money::parseByDecimal(2000.23, 'USD');
        // $acdd = Money::parseByDecimal(3000.23, 'USD');
        // $acddd = Money::parseByDecimal(4000.10, 'USD');
        // $a = sn_currency()->formatByDecimal([$ac, $acd, $acdd, $acddd]);
        // var_dump($a);
        // exit;



        
        $a = Money::parseByDecimal(1000, 'USD');
        echo $a;
        dd($a);

        echo sn_currency()->subtract(8.5, 4, 56, 99);exit;

        Money::USD(500)->isPositive();


        $a = Money::parseByDecimal(25.23, sn_currency()->getCurrency());
        $b = Money::parseByDecimal(3, sn_currency()->getCurrency());

        $a = $a->multiply($b);

        // $a = sn_currency()->multiply(25, 30);
        // echo $a;

        // // $a = Money::USD(500);
        // $a = Money::USD(13.34);
        echo $a;
        dd($a);
        echo $a->add(12.34);

        exit;

        // $skuPrice = new SkuPrice;
        // $skuPrice->price = 11212.32;
        // $skuPrice->sku_type = 'single';
        // $skuPrice->status = 'up';


        // $skuPrice->save();
        // // return response()->json($skuPrice);

        // echo $skuPrice->price . "<br>";
        // echo $skuPrice->price->formatByDecimal();
        // exit;


        $total = $skuPrice->price->add($skuPrice->status);
        echo $total;exit;


        $skuPrice = SkuPrice::find($skuPrice->id);
        echo "-------------";
        echo $skuPrice->price;
        echo "<br>";
        echo $skuPrice->status;
        
        echo "<br>";
        echo $skuPrice->sku_type;
        dd($skuPrice->price);


        echo Currency::formatAmount(500);exit;


        Money::USD(500)->add(Money::USD(500)); // $10.00
        Money::USD(500)->add(Money::USD(500), Money::USD(500)); // $15.00
        Money::USD(500)->subtract(Money::USD(400)); // $1.00
        Money::USD(500)->subtract(Money::USD(200), Money::USD(100)); // $2.00
        Money::USD(500)->multiply(2); // $10.00
        Money::USD(1000)->divide(2); // $5.00
        Money::USD(830)->mod(Money::USD(300)); // $2.30 -> Money::USD(230)
        Money::USD(-500)->absolute(); // $5.00
        Money::USD(500)->negative(); // $-5.00
        Money::USD(30)->ratioOf(Money::USD(2)); // 15
        Money::USD(500)->isSameCurrency(Money::USD(100)); // true
        Money::USD(500)->equals(Money::USD(500)); // true
        Money::USD(500)->greaterThan(Money::USD(100)); // true
        Money::USD(500)->greaterThanOrEqual(Money::USD(500)); // true
        Money::USD(500)->lessThan(Money::USD(1000)); // true
        Money::USD(500)->lessThanOrEqual(Money::USD(500)); // true
        Money::USD(500)->isZero(); // false
        Money::USD(500)->isPositive(); // true
        Money::USD(500)->isNegative(); // false
        Money::USD(500)->getMoney(); // Instance of \Money\Money
        Money::isValidCurrency('USD'); // true
        Money::isValidCurrency('FAIL'); // false
        Money::getISOCurrencies(); // Load ISO currencies
    }




}
