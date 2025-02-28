<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Wsmallnews\Order\Models\Order;
use Yansongda\Pay\Pay as YansongdaPay;

class PayController extends Controller
{


    protected $config = [
        'wechat' => [
            'default' => [
                // 「必填」商户号，服务商模式下为服务商商户号
                // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
                'mch_id' => '1481069012',
                // 「选填」v2商户私钥
                'mch_secret_key_v2' => '34010835FA24B45BAB96FEFB1A9599B4',
                // 「必填」v3 商户秘钥
                // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
                'mch_secret_key' => '34010835FA24B45BAB96FEFB1A9599B4',
                // 「必填」商户私钥 字符串或路径
                // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
                // 文件名形如：apiclient_key.pem
                'mch_secret_cert' => 'certs/apiclient_key.pem',
                // 「必填」商户公钥证书路径
                // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
                // 文件名形如：apiclient_cert.pem
                'mch_public_cert_path' => 'certs/apiclient_cert.pem',
                // 「必填」微信回调url
                // 不能有参数，如?号，空格等，否则会无法正确回调
                'notify_url' => 'https://xxx.com/notify',
                // 「选填」公众号 的 app_id
                // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
                'mp_app_id' => '',
                // 「选填」小程序 的 app_id
                'mini_app_id' => 'wxc0c841bf3710dab9',
                // 「选填」app 的 app_id
                'app_id' => '',
                // 「选填」服务商模式下，子公众号 的 app_id
                'sub_mp_app_id' => '',
                // 「选填」服务商模式下，子 app 的 app_id
                'sub_app_id' => '',
                // 「选填」服务商模式下，子小程序 的 app_id
                'sub_mini_app_id' => '',
                // 「选填」服务商模式下，子商户id
                'sub_mch_id' => '',
                // 「选填」（适用于 2024-11 及之前开通微信支付的老商户）微信支付平台证书序列号及证书路径，强烈建议 php-fpm 模式下配置此参数
                // 「必填」微信支付公钥ID及证书路径，key 填写形如 PUB_KEY_ID_0000000000000024101100397200000006 的公钥id，见 https://pay.weixin.qq.com/doc/v3/merchant/4013053249
                'wechat_public_cert_path' => [
                    // '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__ . '/Cert/wechatPublicKey.crt',
                    // 'PUB_KEY_ID_0000000000000024101100397200000006' => __DIR__ . '/Cert/publickey.pem',
                ],
                // 「选填」默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
                'mode' => YansongdaPay::MODE_NORMAL,
            ], 
            'aaa' => [
            ]
        ]
    ];

    public function pay(Request $request)
    {


        $payment = $request->input('payment', 'wechat');

        auth()->loginUsingId(1);

        $user = auth()->user();
        $order = Order::find(1);

        $payManager = $user->pay()->config($this->config)->payable($order);

        $payRecord = $payManager->pay($payment);

        if ($payment == 'wechat') {
            $params = [
                'endpoint' => 'mini',
                'openid' => 'oqKgG7SKDmEK-fZoY_yGcQq0363o',
                'description' => 'test',
                'notify_url' => 'https://unit.test/notify/' . $payment,
                '_config' => 'default',
            ];

            $result = $payManager->thirdPrepay('wechat', $payRecord, $params);
        }
    }



    public function notify ($payment) 
    {
        Log::write('pay-notify-comein:');

        $pay = app('sn-pay');
        $payManager = $pay->config($this->config);

        $payManager->thirdNotify($payment, function () {
            
        });
    }

}
