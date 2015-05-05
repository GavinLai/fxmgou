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
	
	public static function showInvalidLogin($msg='非法访问！',$title='错误发生 - 福小蜜') {
    $str = '<!DOCTYPE html>';
    $str.= '<html>';
    $str.= '<head>';
    $str.= '<meta http-equiv="Content-Type" Content="text/html;charset=utf-8" />';
    $str.= '<title>'.$title.'</title>';
    $str.= '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">';
    $str.= '<style type="text/css">html,body,table,tr,td,a{margin:0;padding:0;} html {font-size: 62.5%;} body {font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;text-rendering: optimizeLegibility;} html,body,table{width:100%;height:100%;} table td{text-align:center;vertical-align:middle;font-size:20px;font-size:2rem;font-weight:bold;} table td a{font-size:16px;font-size:1.6rem;font-weight:normal;}</style>';
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