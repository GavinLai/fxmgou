<?php
/**
 * 与订单相关方法
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Order {
  
  /**
   * 检查支付付款与原来订单金额是否一致
   * @param string $order_sn 订单号
   * @param integer $money 金钱(以分为单位)
   * @return boolean
   */
  static function check_paid_money($order_sn, $money) {
    
    $ectb = ectable('order_info');
    $order_amount = D()->raw_query("SELECT `order_amount` FROM {$ectb} WHERE `order_sn`='%s'", $order_sn)->result();
    if (empty($order_amount)) {
      return false;
    }
    $order_amount = intval($order_amount*100);
    
    return $order_amount===$money ? true : false;
  }
  
  /**
   * 根据订单号获取订单中跟支付相关的信息
   * @param string $order_sn
   */
  static function get_order_paid_info($order_sn) {
    $ectb = ectable('order_info');
    $row  = D()->raw_query("SELECT `order_id`,`order_sn`,`user_id`,`order_status`,`shipping_status`,`pay_status`,`pay_id`,`order_amount` FROM {$ectb} WHERE `order_sn`='%s'", $order_sn)
               ->get_one();
    return !empty($row) ? $row : [];
  }

  /**
   * 插入订单动作日志
   */
  static function order_action_log($order_id, Array $insert_data) {
    if (empty($order_id)) return false;
    $oinfo = D()->get_one("SELECT `order_id`,`order_status`,`shipping_status`,`pay_status` FROM ".ectable('order_info')." WHERE `order_id`=%d", $order_id);
    $init_data = [
      'order_id'       => $order_id,
      'action_user'    => 'buyer',
      'order_status'   => $oinfo['order_status'],
      'shipping_status'=> $oinfo['shipping_status'],
      'pay_status'     => $oinfo['pay_status'],
      'action_place'   => 0,
      'action_note'    => '',
      'log_time'       => simphp_gmtime(),
    ];
    $insert_data = array_merge($init_data, $insert_data);
     
    $rid = D()->insert(ectable('order_action'), $insert_data, true, true);
    return $rid;
  }
  
  /**
   * 
   */
  static function order_paid() {
    
  }
  
}
 
/*----- END FILE: class.Order.php -----*/