<?php
/**
 * 通用模块，所有模块被调用时，都会先执行的模块
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Common_Controller extends Controller {

  /**
   * 登录白名单，白名单中的请求地址不需登录，其他都需要。
   * @var array
   */
  public static $loginWhiteList = [
    'user/oauth',
    'user/oauth/%s',
    'node/show/%s',
    'node/show/%s/%d',
    'default/guide',
  ];
  
  /**
   * hook menu
   *
   * @return array
   */
  public static function menu_init() {
    return [
      '!^cate/(\d+)$!i'       => 'default/cate/$1',
      '!^cate/(\d+)/(\d+)$!i' => 'node/cate/$1/$2',
      '!^edit/(\d+)$!i'       => 'node/edit/$1',
      '!^item/(\d+)$!i'       => 'default/item/$1',
      '!^explore$!i'          => 'default/explore',
      '!^about$!i'            => 'default/about',
      '!^guide$!i'            => 'default/guide',
    ];
  }
  
  /**
   * on dispatch before hook
   * 
   * @param Request $request
   * @param Response $response
   */
  public static function on_dispatch_before(Request $request, Response $response) {
    
    // 检查q是否在白名单中
    $loginIgnore = false;
    $q = $request->q();
    if (!empty($q)) {
      foreach(self::$loginWhiteList AS $key) {
        if (SimPHP::qMatchPattern($key, $q)) {
          $loginIgnore = true;
          break;
        }
      }
    }
    
    // 检查登录状态
    /*
    if(!$loginIgnore && !Member::isLogined()){
      import('user/*');
      $user_Controller = new User_Controller();
      $user_Controller->login($request, $response);
      exit;
    }
    */
  }
  
  /**
   * on dispatch after hook
   *
   * @param Request $request
   * @param Response $response
   */
  public static function on_dispatch_after(Request $request, Response $response) {
    //echo "<p>on dispatch after</p>";
  }
  
  /**
   * on shutdown hook
   * 
   * @param Request $request
   * @param Response $response
   */
  public static function on_shutdown(Request $request, Response $response) {
    //echo "<p>on shutdown</p>";
  }
  
}
 
/*----- END FILE: Common_Controller.php -----*/