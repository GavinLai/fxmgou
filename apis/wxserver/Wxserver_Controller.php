<?php
/**
 * Weixin Server Controller
 *
 * @author Gavin
 */
defined('IN_SIMPHP') or die('Access Denied');

class Wxserver_Controller extends Controller {
  
  public function menu() {
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
    $t = isset($_GET['t']) ? trim($_GET['t']) : '';
    $wx= new Weixin($t);
    if (!isset($_GET['echostr'])) {
      if($wx->checkSignature()){//签名检测
        $wx->responseMsg();
      }
      else {
        echo "";
      }
    }
    else { //接口验证
      $wx->valid();
    }
    exit;
  }

}

/*----- END FILE: Wxserver_Controller.php -----*/