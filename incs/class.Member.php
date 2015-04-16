<?php
/**
 * 与Member相关常用方法
 *
 * @author afar<afarliu@163.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Member{
  
  /**
   * 本地access token的生命周期(秒)
   * @var integer
   */
  const LOCAL_ACCESS_TOKEN_LIFETIME = 604800; //60*60*24*7
  
	/**
	 * 检测用户名合法性
	 * @param string $name
	 */
	public static function checkUsername($name){
		$rs = [false,''];
		$msg = '用户名规则为4-15个字母数字或下划线(首字母不能为数字)';
		$pattern = '/^[a-zA-Z_][a-zA-Z_\d]{3,14}$/';
		$match = preg_match($pattern,$name);
		if($match==0){
			$rs[1] = $msg;
			return $rs;
		}
		
		//敏感用户名检测
		$censorusername = C('stopword.uname');
		foreach($censorusername as $val){
			if(preg_match($val,$name)){
				$msg = '用户名中含有敏感词';
				$rs[1] = $msg;
				return $rs;
			}
		}
		$rs[0] = true;
		return $rs;
	}
	
	/**
	 * 检测密码合法性
	 * @param string $pwd
	 */
	public static function checkPwd($pwd){
		$rs = [false,''];
		$msg = '密码应为6-20个字符';
		$len = strlen($pwd);
		if($len<5||$len>20){
			$rs[1] = $msg;
		}
		$rs[0] = true;
		return $rs;
	}
	
	/**
	 * 检测Email合法性
	 * @param string $email
	 */
	public static function checkEmail($email) {
		$rs = [false,''];
		
		if(strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email)){
			$rs[0] = true;
		}else{
			$rs[1] = '邮箱格式不正确'.$email;
		}
		return $rs;
	}
	
	/**
	 * 检测moblie合法性
	 * @param string $mobile
	 */
	public static function checkMobile($mobile) {
		$rs = [false,''];
		
		if(preg_match("/^[0-9]{11}$/", $mobile)){
			$rs[0] = true;
		}else{
			$rs[1] = '手机号格式不正确';
		}
		return $rs;
	}

	/**
	 * 用户是否登录
	 * @return boolean
	 */
	public static function isLogined(){
		if(isset($_SESSION['uid']) && $_SESSION['uid']>0){
			return true;
		}else{
		  /*
			$openid = Cookie::get('auth_id');
			if(FALSE===$openid){
				return false;
			}else{
				$openid = zf_authcode($openid, 'DECODE' , Config::get('env.au_key'));
				$userinfo = self::getTinyInfoByOpenid($openid);
				if(empty($userinfo)){
					return false;
				}else{
					$_SESSION['uid'] = $userinfo['uid'];
					return true;
				}
			}
			*/
		  return false;
		}
	}
	
	/**
	 * 检查uid是否关注公众号
	 * 
	 * @return boolean
	 */
	public static function isSubscribe($uid) {
	  $b = D()->result("SELECT `subscribe` FROM `{member}` WHERE `uid`=%d",$uid);
	  return $b ? true : false;
	}
	
	public static function getUser(){
		$user = ['uid'=>0];
		if(self::isLogined()){
			$user['uid'] = $_SESSION['uid'];
		}
		return $user;
	}

	public static function autoLogin($openid){
		//$openid = zf_authcode($openid, 'ENCODE' , Config::get('env.au_key'));
		//Cookie::set('auth_id', $openid, PHP_INT_MAX);
	}
	
	/**
	 * 检查对应$openid的用户是否存在
	 *
	 * @param string $openid
	 * @param string $from 用户来源
	 * @return boolean
	 */
	public static function checkExistByOpenid($openid, $from = 'weixin')
	{
	  $uid = 0;
	  if ('weixin'==$from) {
	    $uid = D()->result("SELECT `uid` FROM `{member}` WHERE `openid`='%s' AND `from`='%s'", $openid, $from);
	  }
	  return $uid ? TRUE : FALSE;
	}
	
	/**
	 * 返回最小用户信息字段
	 *
	 * @return string
	 */
	private static function tinyFields()
	{
	  return "`uid`,`openid`,`unionid`,`username`,`nickname`,`sex`,`logo`,`state`,`from`";
	}
	
	/**
	 * 通过$uid获取最小用户信息
	 *
	 * @param integer $uid
	 * @return multitype:
	 */
	public static function getTinyInfoByUid($uid)
	{
	  return D()->get_one("SELECT ".self::tinyFields()." FROM `{member}` WHERE `uid`=%d", $uid);
	}
	
	/**
	 * 通过$openid获取最小用户信息
	 * @param string $openid
	 * @param string $from 用户来源
	 * @return multitype:
	 */
	public static function getTinyInfoByOpenid($openid, $from = 'weixin')
	{
	  return D()->get_one("SELECT ".self::tinyFields()." FROM `{member}` WHERE `openid`='%s' AND `from`='%s'", $openid, $from);
	}
	
	/**
	 * 创建一个新用户
	 *
	 * @param array $data
	 * @param string $from 用户来源
	 * @return boolean|number
	 */
	public static function createUser(Array $data, $from = 'weixin')
	{
	  if (empty($data)) return FALSE;
	  $now  = simphp_time();
	  $data = array_merge($data,['regip'=>Request::ip(), 'regtime'=>$now, 'posttime'=>$now, 'salt'=>gen_salt(), 'state'=>1, 'from'=>$from]);
	  $uid  = D()->insert('member', $data);
	  if($uid>0){
	    if (empty($data['username'])) {
	      D()->update('member' , ['username'=>$uid] , ['uid'=>$uid]);
	    }
	    return $uid;
	  }
	  return FALSE;
	}
	
	/**
	 * 通过openid(或uid,当openid是整形数据时)更新用户信息
	 *
	 * @param array $data
	 * @param string $openid
	 * @param string $from
	 * @return boolean|number
	 */
	public static function updateUser(Array $data, $openid = '', $from = 'weixin')
	{
	  if (empty($data)) return FALSE;
	  $data = array_merge($data,['posttime'=>simphp_time()]);
	  
	  $where= [];
	  if (is_numeric($openid) && $openid>0) {
	    $where = ['uid'=>$openid];
	  }
	  else {
	    $where = ['openid'=>$openid, 'from'=>$from];
	  }
	
	  $effcnt=0;
	  if (!empty($where)) {
	    $effcnt = D()->update('member', $data, $where);
	  }
	  return $effcnt ? $effcnt : FALSE;
	}
	
	/**
	 * 设置本地登录信息
	 * 
	 * @param integer $uid
	 */
	public static function setLocalLogin($uid)
	{
	  if (!$uid) return;
	  
	  //设置登录session uid
	  $_SESSION['uid'] = $uid;
	  
	  //更新登录记录
	  self::updateUser(['lastip'=>Request::ip(), 'lasttime'=>simphp_time()], $uid);
	}
	
}
?>