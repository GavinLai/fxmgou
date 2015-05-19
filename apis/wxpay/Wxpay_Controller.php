<?php
/**
 * 
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Wxpay_Controller extends Controller {
  
  public function menu()
  {
    return [
      
    ];
  }
  
  /**
   * hook init
   *
   * @param string $action
   * @param Request $request
   * @param Response $response
   */
  public function init($action, Request $request, Response $response)
  {
  
  }
  
  /**
   * default action 'index'
   *
   * @param Request $request
   * @param Response $response
   */
  public function index(Request $request, Response $response)
  {
    
  }
  
  /**
   * action 'notify'
   * 微信支付统一下订单回调
   *
   * @param Request $request
   * @param Response $response
   */
  public function notify(Request $request, Response $response)
  {
    trace_debug('api_wxpay_notify_get', $_GET);
    trace_debug('api_wxpay_notify_post', $_POST);
  }
  
}
 
/*----- END FILE: Wxpay_Controller.php -----*/