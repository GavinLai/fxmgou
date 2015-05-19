<?php
/**
 * 微信支付接口入口类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

//定义微信支付SDK库根目录
define('WXPAY_SDK_ROOT', __DIR__.'/libs/wxpay/');

require_once WXPAY_SDK_ROOT."lib/WxPay.Api.php";

//初始化日志
$logHandler= new CLogFileHandler(SIMPHP_ROOT.LOG_DIR."/wxpay_".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class Wxpay {
  
  /**
   * 下订单回调接口
   * 
   * @var string
   */
  private static $notify_url = 'http://api.fxmgou.com/wxpay/notify';
  
  /**
   * 统一下单接口
   * 
   * @param array $order 站内订单数组
   * @param string $openId openid
   * @return string
   */
  public static function unifiedOrder(Array $order, $openId = '') {
    
    if (empty($order)) {
      return '';
    }
    
    include_once WXPAY_SDK_ROOT."unit/WxPay.JsApiPay.php";
    
    //获取用户openid
    $tools = new JsApiPay();
    if (empty($openId)) {
      $openId = $tools->GetOpenid();      
    }
    
    //统一下单
    $now   = time();
    $input = new WxPayUnifiedOrder();
    $input->SetBody("test");
    //$input->SetDetail();
    //$input->SetAttach("test");
    $input->SetOut_trade_no($order['order_sn']);
    $input->SetTotal_fee(intval($order['order_amount']*100)); //'分'为单位
    $input->SetTime_start(date('YmdHis', $now));
    $input->SetTime_expire(date('YmdHis', $now + 60*15)); //15分钟内支付有效
    $input->SetGoods_tag(''); //商品标记，代金券或立减优惠功能的参数
    $input->SetNotify_url(self::$notify_url);
    $input->SetTrade_type('JSAPI');
    $input->SetOpenid($openId);
    $order_wx = WxPayApi::unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order_wx);
    return $jsApiParameters;
    
  }
  
}

/*----- END FILE: class.Wxpay.php -----*/