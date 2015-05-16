<?php
/**
 * Static functions class 'Fn::', extend from Func:: 
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
class Fn extends Func {
  
  /**
   * 默认头像
   */
  public static function default_logo(){
    return '/misc/images/avatar/default_ulogo.png';
  }
  
  /**
   * 显示错误消息
   *
   * @param string $msg 显示的消息
   * @param boolean $with_back_btn 带“返回”按钮
   * @param string $title 文档标题
   */
  public static function showErrorMessage($msg='非法访问！', $with_back_btn=false, $title='错误发生 - 福小蜜') {
    $ctrl_str = '';
    if ($with_back_btn) {
      $ctrl_str .= '<a href="javascript:history.back()">返回</a>&nbsp;|&nbsp;';
    }
    $str = '<!DOCTYPE html>';
    $str.= '<html>';
    $str.= '<head>';
    $str.= '<meta http-equiv="Content-Type" Content="text/html;charset=utf-8" />';
    $str.= '<title>'.$title.'</title>';
    $str.= '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">';
    $str.= '<style type="text/css">html,body,table,tr,td,a{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;} html {font-size: 62.5%;} body {font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;text-rendering: optimizeLegibility;} html,body{display:block;width:100%;height:100%;} table{width:100%;height:100%;border-top:4px solid #44b549;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;} table td{text-align:center;vertical-align:middle;font-size:22px;font-size:2.2rem;font-weight:bold;} table td a{font-size:18px;font-size:1.8rem;font-weight:normal;}</style>';
    $str.= '</head>';
    $str.= '<body>';
    $str.= '<table><tr><td>'.$msg.'<br/><br/>'.$ctrl_str.'<a href="javascript:;" id="closeWindow">关&nbsp;闭</a></td></tr></table>';
    $str.= '<script>var readyFunc = function(){document.querySelector("#closeWindow").addEventListener("click", function(e){if(typeof WeixinJSBridge === "undefined") window.close();else WeixinJSBridge.invoke("closeWindow",{},function(res){});return false;});};if (typeof WeixinJSBridge === "undefined") {document.addEventListener("WeixinJSBridgeReady", readyFunc, false);} else {readyFunc();}</script>';
    $str.= '</body>';
    $str.= '</html>';
    echo $str;
    exit;
  }
  
  /**
   * 生成订单号
   * @return string
   */
  public static function gen_order_no() {
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    return 'E'.date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
  }
  
}
 
/*----- END FILE: class.Fn.php -----*/