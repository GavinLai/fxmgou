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
   * @var constant
   */
  const NOFIFY_URL = 'http://api.fxmgou.com/wxpay/notify';
  
  /**
   * 交易类型常量，共4个可选值
   * @var constant
   */
  const TRADE_TYPE_JSAPI  = 'JSAPI';
  const TRADE_TYPE_NATIVE = 'NATIVE';
  const TRADE_TYPE_APP    = 'APP';
  const TRADE_TYPE_WAP    = 'WAP';
  
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
    
    $order_detail = '';
    if (!empty($order['order_goods'])) {
      foreach ($order['order_goods'] As $g) {
        $order_detail = $g['goods_name'].'('.$g['goods_price'].'x'.$g['goods_number']."),";
      }
      $order_detail = rtrim($order_detail,",");
    }
    
    //统一下单
    if (empty($order['wxpay_data'])) {
      $now   = time();
      $input = new WxPayUnifiedOrder();
      $input->SetBody("福小蜜商品");
      $input->SetDetail($order_detail);
      //$input->SetAttach("test");
      $input->SetOut_trade_no($order['order_sn']);
      $input->SetTotal_fee(intval($order['order_amount']*100)); //'分'为单位
      $input->SetTime_start(date('YmdHis', $now));
      $input->SetTime_expire(date('YmdHis', $now + 60*15)); //15分钟内支付有效
      $input->SetGoods_tag(''); //商品标记，代金券或立减优惠功能的参数
      $input->SetNotify_url(self::NOFIFY_URL);
      $input->SetTrade_type(self::TRADE_TYPE_JSAPI);
      $input->SetOpenid($openId);
      
      $order_wx = WxPayApi::unifiedOrder($input);
      trace_debug('wxpay_unifiedorder_wxreturn', $order_wx);
      
      if ('SUCCESS'==$order_wx['return_code'] && 'SUCCESS'==$order_wx['result_code']) { //保存信息以防再次重复提交
        $wxpay_data = [
          'appid'      => $order_wx['appid'],
          'mch_id'     => $order_wx['mch_id'],
          'trade_type' => $order_wx['trade_type'],
          'prepay_id'  => $order_wx['prepay_id']
        ];
        if (isset($order_wx['code_url'])) {
          $wxpay_data['code_url'] = $order_wx['code_url'];
        }
        Goods::orderUpdate(['wxpay_data'=>json_encode($wxpay_data)], $order['order_id']);
      }
    }
    else {
      $order_wx = json_decode($order['wxpay_data'], true);
      trace_debug('wxpay_unifiedorder_cachedb', $order_wx);
    }
    
    $jsApiParameters = $tools->GetJsApiParameters($order_wx);
    
    //trace_debug('wxpay_unifiedorder_jsparams', $jsApiParameters);
    return $jsApiParameters;
    
  }
  
}

/*----- END FILE: class.Wxpay.php -----*/