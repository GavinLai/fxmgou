<?php
/**
 * Node Model
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class User_Model extends Model {

	public static function saveFeedback($data){
		$fid = D()->insert('feedback', $data);
		return $fid;
	}

	public static function getCollectByType($uid, $type, $limit=30){
	  $records = [];
	  if (!$uid) return $records;
		if($type=="word"){
			$sql = "SELECT a.aid,a.timeline,n.*  FROM {action} AS a LEFT JOIN {node} AS n ON a.nid=n.nid WHERE a.uid=%d AND a.type_id='%s' AND a.action='collect' ";
			$sqlcnt = "SELECT COUNT(*) FROM {action} WHERE uid=%s AND type_id='%s' AND action='collect'";
			$records = D()->pager_query($sql, $limit, $sqlcnt, 0, $uid, $type)->fetch_array_all();
		}else{
			$sql = "SELECT a.aid,a.timeline,n.*,t.* FROM {action} AS a LEFT JOIN {node} AS n ON a.nid=n.nid LEFT JOIN {node}_{$type} AS t ON n.nid=t.tnid WHERE a.uid=%d AND a.type_id='%s' AND action='collect'";
			$sqlcnt = "SELECT COUNT(*) FROM {action} WHERE uid=%s AND type_id='%s' AND action='collect' ";
			$records = D()->pager_query($sql, $limit, $sqlcnt, 0, $uid, $type)->fetch_array_all();
		}

		return $records;
	}
	
	public static function checkAccessToken($token){
		$record = D()->get_one("SELECT * FROM {access_token} WHERE token='%s' ", $token);
		if(empty($record)){
			return FALSE;
		}
		else{
			$timestamp = time();
			if($timestamp>$record['lifetime']){
				return FALSE;
			}
			else{
				//D()->update('access_token', ['lifetime'=>0], ['openid'=>$record['openid']]);
				return $record['openid'];
			}
		}
	}
	
	/**
	 * 检查用户信息完成度，nickname或logo没有的话都重定向请求OAuth2详细认证获取资料
	 * @param array $uinfo
	 * @return boolean
	 */
	public static function checkUserInfoCompleteDegree($uinfo, $refer = '/') {
	  if (empty($uinfo['nickname']) || empty($uinfo['logo'])) { //只要两个其中一个为空，都请求OAuth2详细认证
	    if ( !isset($_SESSION['wxoauth_reqcnt']) ) $_SESSION['wxoauth_reqcnt'] = 0;
	    $_SESSION['wxoauth_reqcnt']++;
	    if ($_SESSION['wxoauth_reqcnt'] < 4) { //最多尝试2次，避免死循环
	      (new Weixin())->authorizing('http://'.Request::host().'/user/oauth/weixin?refer='.$refer, 'detail');
	    }
	  }
	  return true;
	}
	
	/**
	 * 显示错误消息
	 * 
	 * @param string $msg
	 * @param string $title
	 */
	public static function showInvalidLogin($msg='非法访问！',$title='错误发生 - 福小蜜') {
    $str = '<!DOCTYPE html>';
    $str.= '<html>';
    $str.= '<head>';
    $str.= '<meta http-equiv="Content-Type" Content="text/html;charset=utf-8" />';
    $str.= '<title>'.$title.'</title>';
    $str.= '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">';
    $str.= '<style type="text/css">html,body,table,tr,td,a{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;} html {font-size: 62.5%;} body {font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;text-rendering: optimizeLegibility;} html,body{display:block;width:100%;height:100%;} table{width:100%;height:100%;border-top:4px solid #44b549;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;} table td{text-align:center;vertical-align:middle;font-size:22px;font-size:2.2rem;font-weight:bold;} table td a{font-size:18px;font-size:1.8rem;font-weight:normal;}</style>';
    $str.= '</head>';
    $str.= '<body>';
    $str.= '<table><tr><td>'.$msg.'<br/><br/><a href="javascript:;" id="closeWindow">关&nbsp;闭</a></td></tr></table>';
    $str.= '<script>var readyFunc = function(){document.querySelector("#closeWindow").addEventListener("click", function(e){if(typeof WeixinJSBridge === "undefined") window.close();else WeixinJSBridge.invoke("closeWindow",{},function(res){});return false;});};if (typeof WeixinJSBridge === "undefined") {document.addEventListener("WeixinJSBridgeReady", readyFunc, false);} else {readyFunc();}</script>';
    $str.= '</body>';
    $str.= '</html>';
    echo $str;
    exit;
	}
	
	
	
	
	
	
}