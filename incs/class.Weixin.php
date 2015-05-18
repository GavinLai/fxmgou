<?php
/**
 * 微信接口相关类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

require_once (SIMPHP_INCS . '/libs/ApiRequest/class.ApiRequest.php');

class Weixin {
  
  /**
   * Some configure values
   */
	public $appId         = '';
	public $appSecret     = '';
	public $token         = '';
	public $encodingAesKey= '';
	public $paySignKey    = '';
	public $caFile        = '';
	
	/**
	 * Allow weixin public account
	 * @var array
	 */
	public static $allowAccount = array('fxmgou');

	/**
	 * Weixin constant name
	 * @var constant
	 */
	const PLUGIN_JSSDK  = 'jssdk';
	const PLUGIN_JSADDR = 'jsaddr';
	
	/**
	 * All weixin plugins name
	 * @var array
	 */
	public static $plugins = array(self::PLUGIN_JSSDK, self::PLUGIN_JSADDR);
	
	/**
	 * WeixinHelper instance
	 * @var WeixinHelper
	 */
	public $helper;
	
	/**
	 * OAuth2 instance
	 * @var OAuth2
	 */
	public $oauth;

	/**
	 * WeixinJSSDK instance
	 * @var WeixinJSSDK
	 */
	public $jssdk;
	
	/**
	 * WeixinJSAddr instatnce
	 * 
	 * @var WeixinJSAddr
	 */
	public $jsaddr;
	
	/**
	 * API address url prefix
	 * @var array
	 */
	public static $apiUrlPrefix = array(
	  'api_cgi'   => 'https://api.weixin.qq.com/cgi-bin',
	  'api_sns'   => 'https://api.weixin.qq.com/sns',
	  'open_conn' => 'https://open.weixin.qq.com/connect',
	  'qyapi_cgi' => 'https://qyapi.weixin.qq.com/cgi-bin',
	);
	
	/**
	 * 响应消息结构
	 * @var array
	 */
	public static $msgTpl = array(
	  //文本消息
	  'text' => "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>",
	  //图片消息
	  'image'=> "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>",
	  //语音消息
	  'voice'=> "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>",
	  //视频消息
	  'video'=> "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video>
</xml>",
	  //音乐消息
	  'music'=> "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>",
	  //图文消息 - 总结构, 注意：ArticleCount <=10
	  'news' => "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%d</ArticleCount>
<Articles>%s</Articles>
</xml>",
	  //图文消息 - 单条记录
	  'news_item' => "<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>"
	);

	/**
	 * 初始化配置
	 * @param array $plugins, 额外的插件，如 'jssdk', 'wxpay' 等
	 * @param string $target, 目标平台，可选值：'fxmgou' 等
	 */
	private function init(Array $plugins = array(), $target = 'fxmgou')
	{
	  if (!in_array($target, self::$allowAccount)) {
	    throw new Exception("Weixin public account not allowed: {$target}");
	  }
	  
	  $wx_config       = Config::get('api.weixin_'.$target);
	  
		$this->appId     = $wx_config['appId'];
		$this->appSecret = $wx_config['appSecret'];
		$this->token     = $wx_config['token'];
		$this->encodingAesKey = $wx_config['encodingAesKey'];
		$this->paySignKey= $wx_config['paySignKey'];
		$this->caFile    = SIMPHP_INCS.'/incs/libs/weixin/cacerts.pem';
		
		$this->helper    = new WeixinHelper($this);
		$this->oauth     = new OAuth2(array('client_id'=>$this->appId,'secret_key'=>$this->appSecret,'response_type'=>'code','scope'=>'snsapi_base','state'=>'base'),'weixin');
		if (in_array(self::PLUGIN_JSSDK, $plugins)) {
		  $this->jssdk   = new WeixinJSSDK($this->appId, $this);
		}
		if (in_array(self::PLUGIN_JSADDR, $plugins)) {
		  $this->jsaddr  = new WeixinJSAddr($this->appId, $this);
		}
	}
	
	/**
	 * 初始化构造函数
	 * @param array $plugins, 额外的插件，如 'jssdk', 'wxpay' 等
	 * @param string $target, 目标平台，可选值：'fxmgou' 等
	 */
	public function __construct(Array $plugins = array(), $target = 'fxmgou')
	{
		$this->init($plugins, $target); //该句必须出现在所有对外方法的最开始
	}
	
	/**
	 * 验证接口地址有效性
	 */
	public function valid()
	{
	  $echoStr = $_GET['echostr'];
    if($this->checkSignature()){
      echo $echoStr;
    }
    else {
      echo '';
    }
    exit;
	}

	/**
	 * 检查签名
	 * @return boolean
	 */
  public function checkSignature()
  {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce     = $_GET["nonce"];
    
    $token  = $this->token;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING); //use SORT_STRING rule
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    
    if( $tmpStr == $signature ){
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * 响应微信服务器回调消息
   */
  public function responseMsg()
  {
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    $respMsg = '';
    if (!empty($postStr)){
      /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
       the best way is to check the validity of xml by yourself */
      libxml_disable_entity_loader(TRUE);
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      switch ($postObj->MsgType) {
        case 'event':
          $respMsg = $this->dealEventMsg($postObj);
          break;
        case 'text':
          $respMsg = $this->dealTextMsg($postObj);
          break;
        case 'image':
          $respMsg = $this->dealImageMsg($postObj);
          break;
        case 'location':
          break;
        case 'voice':
          $respMsg = $this->dealVoiceMsg($postObj);
          break;
        case 'video':
          break;
        case 'link':
          break;
        default:
          //unknown msg type
          break;
      }
    }
    echo $respMsg;
    exit;
  }
  
  /**
   * 处理事件消息
   *
   * @param SimpleXMLElement $postObj
   * @return response msg string
   */
  private function dealEventMsg($postObj)
  {
    $fromUsername= $postObj->FromUserName;
    $toUsername  = $postObj->ToUserName;
    $openid      = $fromUsername;
    $reqtime     = intval($postObj->CreateTime);
    $restime     = time();
    $contentText = '';
    $responseText= '';
    switch ($postObj->Event) {
      case 'subscribe':
        $contentText = $this->helper->onSubscribe($openid, $reqtime, $toUsername);
        break;
      case 'unsubscribe':
        $this->helper->onUnsubscribe($openid, $reqtime);
        break;
      case 'SCAN':
        break;
      case 'CLICK':
        $base_url = Config::get('env.site.mobile');
        switch ($postObj->EventKey) {
          case '100': //最新文章
            $contentText = $this->helper->latestArticles();
            break;
          case '101': //关于小蜜
            $contentText = $this->helper->about();
            break;
        }
        break;
      case 'LOCATION':
        $longitude = $postObj->Longitude;
        $latitude  = $postObj->Latitude;
        $precision = $postObj->Precision;
        $this->helper->onLocation($openid, $reqtime, $longitude, $latitude, $precision);
        break;
      case 'VIEW':
        break;
      case 'MASSSENDJOBFINISH':
        break;
      default:
        //未知事件
    }
    
    if (!empty($contentText)) {
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }
    return $responseText;
  }
  
  /**
   * 处理文本消息
   * 
   * @param SimpleXMLElement $postObj
   * @return response msg string
   */
  private function dealTextMsg($postObj)
  {
    $fromUsername = $postObj->FromUserName;
    $toUsername   = $postObj->ToUserName;
    $keyword      = trim($postObj->Content);
    $openid       = $fromUsername;
    $reqtime      = intval($postObj->CreateTime);
    $restime      = time();
    trace_debug('weixin_reply_text',$keyword);
    $contentText  = $this->helper->onTextQuery($keyword, $openid, $reqtime);
    $responseText = '';
    if (!empty($contentText)&&!is_array($contentText)) {
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }
    elseif(!empty($contentText)&&is_array($contentText)){
      //msgTpl
      $text = '';
      $base_url = Config::get('env.site.mobile');
      foreach($contentText as $val){
        switch ($val['type_id']) {
          case 'word':
            $title = "\n".$val['content'];
            $desc = $val['content'];
            $pic = '';
            $link = $base_url."/node/{$val['nid']}/edit";
            break;
          case 'card':
            $title = $val['content']=='' ? $val['title']:$val['content'];
            $desc = $val['content'];
            $pic  = preg_match("!^(http|https):\/\/!i", $val['cover_url']) ? $val['cover_url'] : $base_url.$val['cover_url'];
            $link = $base_url."/node/{$val['nid']}/edit";
            break;
          case 'music':
            $title = $val['title'].' - '.$val['singer_name'];
            $desc = $val['title'].' - '.$val['singer_name'];
            $pic  = preg_match("!^(http|https):\/\/!i", $val['bg_url']) ? $val['bg_url'] : $base_url.$val['bg_url'];
            $link = $base_url."/node/{$val['nid']}/edit";
            break;
          case 'gift':
            $title = $val['title'];
            $desc = $val['title'].' - '.strip_tags($val['desc']);
            $pic  = preg_match("!^(http|https):\/\/!i", $val['goods_url']) ? $val['goods_url'] : $base_url.$val['goods_url'];
            $link = $base_url."/mall/detail/{$val['nid']}";
            break;                                           
          default:
            # code...
            break;
        }
        $text .= sprintf(self::$msgTpl['news_item'], $title, $desc, $pic, $link);
      }
      $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($contentText),$text);
    }
    else{
      $baidu_sou = "https://www.baidu.com/s?wd={$keyword}";
      $contentText = "抱歉，没有找到你想要的东西！小蜜帮你<a href=\"{$baidu_sou}\">度娘一下</a>吧/::P";
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }
    return $responseText;
  }
  
  /**
   * 处理语音识别结果
   * 
   * @param SimpleXMLElement $postObj
   * @return response msg string
   */
  private function dealVoiceMsg($postObj)
  {
    $keyword      = trim($postObj->Recognition);
    trace_debug('weixin_reply_voice',$keyword);
    $fromUsername = $postObj->FromUserName;
    $toUsername   = $postObj->ToUserName;
    $openid       = $fromUsername;
    $reqtime      = intval($postObj->CreateTime);
    $restime      = time();
    
    if (''==$keyword) {
      $contentText = "抱歉，没听清你说什么，请重试？";
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
      return $responseText;
    }
    
    $contentText  = $this->helper->onVoiceQuery($keyword, $openid, $reqtime);
    $responseText = '';
    if (!empty($contentText)) {
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }
    else {
      $baidu_sou = "https://www.baidu.com/s?wd={$keyword}";
      $contentText = "抱歉，没找到你说的：\n“{$keyword}”\n小蜜帮你<a href=\"{$baidu_sou}\">度娘一下</a>吧/::P";
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }
    return $responseText;
  }
  
  /**
   * 处理图片类消息
   * 
   * @param SimpleXMLElement $postObj
   * @return response msg string
   */
  private function dealImageMsg($postObj)
  {
    return '';    
  }
  
  /**
   * 微信接口通用调用函数
   * 
   * @param string $uri_path URI path
   * @param array $params 对应请求参数
   * @param string $method http请求方法：GET,POST
   * @param string $type API地址类型，对应self::$apiUrlPrefix的key部分：api_cgi,api_sns,open_conn
   * @return mixed JSON Array or string
   */
  public function apiCall($uri_path, $params=array(), $method='get', $type='api_cgi')
  {
    if (!in_array($type, array_keys(self::$apiUrlPrefix))) {
      return false;
    }
    $method = strtolower($method);
    if (!in_array($method, array('get','post'))) {
      return false;
    }
    if (empty($uri_path)) {
      return false;
    }
    
    $requrl = self::$apiUrlPrefix[$type] . $uri_path;
    $req = new ApiRequest(['method'=>$method,'protocol'=>'https','timeout'=>60,'timeout_connect'=>30]);
    return $req->setUrl($requrl)->setParams($params)->send()->recv(TRUE);
  }
  
  /**
   * 获取微信接口需要的基本型(basic)AccessToken
   * 
   * @return string access token
   */
  public function fecthAccessToken()
  {
    $type   = 'basic';
    $result = $this->helper->onFetchAccessTokenBefore($type, $this->appId);
    if (!empty($result)) {
      return $result;
    }
    
    $ret    = array();
    $params = array(
      'grant_type' => 'client_credential',
      'appid'      => $this->appId,
      'secret'     => $this->appSecret,
    );
    $ret = $this->apiCall('/token', $params);
    if (!empty($ret['errcode'])) {
      return false;
    }
    $result = $ret['access_token'];
    
    if (!empty($result)) {
      $this->helper->onFetchAccessTokenSuccess($type, $this->appId, '', $ret);
    }
    return $result;
  }

  /**
   * 创建菜单
   * 
   * @param mixed(string|array) $data
   * @return boolean
   */
  public function createMenu($data = '')
  {
    if (empty($data)) return false;
    if (is_array($data)) $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $access_token = $this->fecthAccessToken();
    $ret = $this->apiCall("/menu/create?access_token={$access_token}", $data, 'post');
    if (0===$ret['errcode']) {
      return true;
    }
    return false;
  }
  
  /**
   * 获取微信用户基本型信息
   * 
   * @param string $openid
   * @return array
   */
  public function userInfo($openid, $lang = 'zh_CN')
  {
    $access_token = $this->fecthAccessToken();
    $params = array(
      'access_token'=> $access_token,
      'openid'      => $openid,
      'lang'        => $lang,
    );
    return $this->apiCall("/user/info", $params, 'get');
  }
  
  /**
   * 通过OAuth2获取微信用户详细信息
   * @param string $openid
   * @param string $access_token
   * @param string $lang
   * @return boolean|Ambigous <mixed, boolean, multitype:boolean multitype: >
   */
  public function userInfoByOAuth2($openid, $access_token, $lang = 'zh_CN')
  {
    $params = array(
      'access_token'=> $access_token,
      'openid'      => $openid,
      'lang'        => $lang,
    );
    return $this->apiCall("/userinfo", $params, 'get', 'api_sns');
  }
  
  /**
   * 微信OAuth2授权
   * 
   * @param string $redirect_uri
   * @param string $state 可选值：'base' or 'detail'
   */
  public function authorizing($redirect_uri, $state = 'base')
  {
    if (!in_array($state,array('base','detail'))) $state = 'base';
    $scope = 'detail'==$state ? 'snsapi_userinfo' : 'snsapi_base';
    $url = $this->oauth->setConfig(array('redirect_uri'=>$redirect_uri,'scope'=>$scope,'state'=>$state))->authorize_url();
    Response::redirect($url);
  }
  
  /**
   * 换取 access_token
   * 
   * @param string $code
   * @return mixed(string|array|boolean)
   */
  public function request_access_token($code)
  {
    return $this->oauth->request_access_token($code);
  }
  
  //~ the following is some util functions
  
  /**
   * 是否微信浏览器
   * 
   * @return boolean
   */
  public static function isWeixinBrowser() {
    $b = strrpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false;
    return !$b;
  }
  
  /**
   * 获取微信浏览器版本号
   * 
   * @return int|string
   */
  public static function browserVer() {
    $ver = 0;
    if (preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $_SERVER['HTTP_USER_AGENT'], $matches)) {
      $ver = $matches[2];
    }
    return $ver;
  }
  
  /**
   * 创建随机 nonce 字符串
   * 
   * @param string $length
   * @return string
   */
  public static function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
  
  /**
   * 返回当前请求的精确url地址
   * @return string
   */
  public static function requestUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    return $url;
  } 
  
}

/**
 * Weixin JS-SDK 类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
class WeixinJSSDK {
  
  /**
   * App Id
   * 
   * @var string
   */
  private $appId;
  
  /**
   * App Secret
   * 
   * @var string
   */
  private $appSecret;
  
  /**
   * js sdk file path
   * 
   * @var string
   */
  private static $sdkFile = 'http://res.wx.qq.com/open/js/jweixin-1.0.0.js';
  
  /**
   * Weixin Object
   * @var Weixin
   */
  private $wx;
  
  public function __construct($appId, Weixin $wx = NULL) {
    $this->appId = $appId;
    $this->wx    = $wx;
  }
  
  /**
   * 获取前端JS-SDK需要的签名包数据
   * 
   * @return array
   */
  private function getSignPackage() {
    
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $url = $this->wx->requestUrl();
  
    $timestamp = time();
    $nonceStr = $this->wx->createNonceStr();
  
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
  
    $signature = sha1($string);
  
    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage;
  }
  
  /**
   * 返回JS-SDK文件路径
   * @return string
   */
  public static function getSdkFile() {
    return self::$sdkFile;
  }
  
  /**
   * 获取jsapi_ticket
   * 
   * @return string
   */
  private function getJsApiTicket() {
    
    $type   = 'jsapi';
    $result = $this->wx->helper->onFetchAccessTokenBefore($type, $this->appId);
    if (!empty($result)) {
      return $result;
    }
    
    $accessToken = $this->wx->fecthAccessToken();
    
    $ret    = array();
    $params = array(
      'type'         => 'jsapi',
      'access_token' => $accessToken
    );
    $ret = $this->wx->apiCall('/ticket/getticket', $params);
    if (!empty($ret['errcode'])) {
      return false;
    }
    $result = $ret['ticket'];
    
    if (!empty($result)) {
      $this->wx->helper->onFetchAccessTokenSuccess($type, $this->appId, '', $ret);
    }
    return $result;
  }
  

  /**
   * 获取前端html Weinxin JS-SDK引入代码及初始配置
   *
   * @param array $jsApiList
   * @return string
   */
  public function js(Array $jsApiList = array()) {
    if (empty($jsApiList)) {
      $jsApiList = [
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo',
      'chooseImage',
      'previewImage',
      'uploadImage',
      'downloadImage',
      'getNetworkType',
      'openLocation',
      'getLocation',
      'hideOptionMenu',
      'showOptionMenu',
      'closeWindow',
      'scanQRCode',
      'chooseWXPay',
      ];
    }
  
    $debugWhiteList = C('env.debug_white_list');
    $signPackage = $this->getSignPackage();
    $jsApiStr    = "'" . implode("','", $jsApiList) . "'";
    $debugStr    = in_array($GLOBALS['user']->uid, $debugWhiteList) ? 'true' : 'false';
    $now         = time();
  
    $jsfile = '<script type="text/javascript" src="'.self::$sdkFile.'"></script>';
    $jsconf =<<<HEREDOC
<script type="text/javascript">
if (typeof(wx)=='object') {
  wx.config({
    debug: {$debugStr},
    appId: '{$signPackage["appId"]}',
    timestamp: {$signPackage["timestamp"]},
    nonceStr: '{$signPackage["nonceStr"]}',
    signature: '{$signPackage["signature"]}',
    jsApiList: [{$jsApiStr}]
  });
  wx.ready(function(){wxData.isReady=true});
}
</script>
HEREDOC;
  
    return $jsfile . $jsconf;
  
  }
}

/**
 * Weixin JS收货地址 类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
class WeixinJSAddr {
  
  /**
   * App Id
   * 
   * @var string
   */
  private $appId;
  
  /**
   * App Secret
   * 
   * @var string
   */
  private $appSecret;
  
  /**
   * Weixin Object
   * @var Weixin
   */
  private $wx;
  
  public function __construct($appId, Weixin $wx = NULL) {
    $this->appId = $appId;
    $this->wx    = $wx;
  }
  
  /**
   * 获取appId
   * @return string
   */
  public function getAppId() {
    return $this->appId;
  }
  
  /**
   * 获取收获地址签名
   * 
   * @param string $appId
   * @param string $url
   * @param string $timeStamp
   * @param string $nonceStr
   * @param string $accessToken
   * @return string 返回签名字串
   */
  public static function sign($appId, $url, $timeStamp, $nonceStr, $accessToken) {
    $str  = "accesstoken={$accessToken}&appid={$appId}&noncestr={$nonceStr}&timestamp={$timeStamp}&url={$url}";
    $sign = sha1($str);
    return $sign;
  }
  
  /**
   * 返回 address 收获地址 js
   */
  public function js($accessToken) {
    $appId     = $this->appId;
    $url       = $this->wx->requestUrl(); // 注意URL一定要动态获取，不能hardcode.
    $timeStamp = time();
    $nonceStr  = $this->wx->createNonceStr();
    $sign      = $this->sign($appId, $url, $timeStamp, $nonceStr, $accessToken);
    
    $js =<<<HEREDOC
<script type="text/javascript">
function wxEditAddress(func_cb) {
  if (typeof(WeixinJSBridge)=='object') {
    WeixinJSBridge.invoke('editAddress', {
      "appId": "{$appId}",
      "scope": "jsapi_address",
      "signType": "sha1",
      "addrSign": "{$sign}",
      "timeStamp": "{$timeStamp}",
      "nonceStr": "{$nonceStr}",
    }, function (res) {
      if(typeof(func_cb)=='function') {
        func_cb(res);
      }
    });
  }
}
</script>
HEREDOC;
    
    return $js;
  }
  
  
}

/**
 * Weixin帮助类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
class WeixinHelper {

  private $from = 'weixin';
  
  /**
   * Weixin Object
   * @var Weixin
   */
  private $wx;
  
  public function __construct(Weixin $wx = NULL) {
    $this->wx = $wx;
  }

  /**
   * 调用未定义函数，则作默认返回空串处理
   * @param string $name
   * @param array $args
   */
  public function __call($name, $args) {
    return '';
  }

  /**
   * 关注事件
   *
   * @param string $openid
   * @param integer $reqtime
   * @param string $toUsername
   * @return string
   */
  public function onSubscribe($openid, $reqtime, $toUsername = '') {
    $uinfo = $this->wx->userInfo($openid);
    //trace_debug('weixin_user_info_base',print_r($uinfo,true));
    if (empty($uinfo['errcode'])) {
      $from  = $this->from;
      $udata = ['openid' => $openid, 'subscribe' => $uinfo['subscribe']];
      if ($uinfo['subscribe']) { //已关注
        $udata['unionid']  = isset($uinfo['unionid']) ? $uinfo['unionid'] : ''; //UnionID
        $udata['subscribe_time'] = $uinfo['subscribe_time'];
        $udata['nickname'] = $uinfo['nickname'];
        $udata['logo']     = $uinfo['headimgurl'];
        $udata['sex']      = $uinfo['sex'];
        $udata['lang']     = $uinfo['language'];
        $udata['country']  = $uinfo['country'];
        $udata['province'] = $uinfo['province'];
        $udata['city']     = $uinfo['city'];
        $udata['auth_method'] = 'base';
      }
      if (!Member::checkExistByOpenid($openid, $from)) { //用户不存在
        Member::createUser($udata, $from);
      }
      else { //用户已存在
        unset($udata['openid'],$udata['auth_method']);
        Member::updateUser($udata, $openid, $from);
      }
    }
    
    $msg = $this->about(1);
    return $msg;
    
  }

  /**
   * 取消关注事件
   *
   * @param string $openid
   * @param integer $reqtime
   * @return string
   */
  public function onUnsubscribe($openid, $reqtime) {
    Member::updateUser(['subscribe' => 0, 'subscribe_time' => $reqtime], $openid, $this->from);
    return '';
  }

  /**
   * 获取位置信息事件
   *
   * @param string $openid
   * @param integer $reqtime
   * @param double $longitude
   * @param double $latitude
   * @param double $precision
   * @return string
   */
  public function onLocation($openid, $reqtime, $longitude, $latitude, $precision) {
    Member::updateUser(['longitude' => $longitude, 'latitude' => $latitude, 'precision' => $precision], $openid, $this->from);
    return '';
  }

  /**
   * 文本关键词查询事件
   *
   * @param string $keyword
   * @param string $openid
   * @param integer $reqtime
   */
  public function onTextQuery($keyword, $openid, $reqtime) {
    $result = '';
    
    if (in_array($keyword, array('小蜜','关于小蜜','关于','福小蜜'))) {
      $result = $this->about();
    }
    elseif ( preg_match('/你|您/', $keyword) && preg_match('/是谁|干什么|做什么/', $keyword) ) {
      $result = $this->about(2);
    }
    elseif (preg_match('/我/', $keyword) && preg_match('/是谁|干什么|做什么/', $keyword)) {
      $result = '你懂的！';
    }
    elseif (in_array($keyword, array('最新文章','最新资讯','文章','资讯'))) {
      $result = $this->latestArticles();
    }
    elseif (preg_match('/百度|度娘/', $keyword)) {
      $result = '这是她的地址：<a href="http://www.baidu.com">www.baidu.com</a>';
    }
    elseif (preg_match('/错了|不喜欢|不感兴趣|不爱|无用|干扰|骚扰/', $keyword)) {
      $result = '不好意思/::~';
    }
    elseif (preg_match('/喜欢|很好|感兴趣|大爱|太棒|真棒|棒极|哇塞|不错|太酷|忒酷|真酷/', $keyword)) {
      $result = '谢谢/::$';
    }
    elseif (preg_match('/无聊|干什么|什么干|做什么|什么做|什么好做|什么好干/', $keyword)) {
      $poems = [
        "终日昏昏醉梦间，\n忽闻春尽强登山。\n因过竹院逢僧话，\n偷得浮生半日闲。",
        "黄梅时节家家雨，\n青草池塘处处蛙。\n有约不来过夜半，\n闲敲棋子落灯花。",
        "无聊夜里无聊梦，\n心语无聊冷若冰。\n叹写无聊抒郁事，\n无聊目送月零丁。",
        "一别之后，\n两地相悬，\n只说是三四月，\n又谁知五六年。\n七弦琴无心弹，\n八行书不可传，\n九连环从中折断，\n十里长亭望眼欲穿，\n百思想，千系念，万般无奈把郎怨。\n万语千言说不完，百无聊赖十倚栏，\n重九登高看孤雁，\n八月中秋月圆人不圆，\n七月半烧香秉烛问苍天，\n 六月伏天人人摇扇我心寒，\n 五月石榴如火，偏遇阵阵冷雨浇花端；\n四月枇杷未黄，我欲对镜心意乱。\n急匆匆，三月桃花随水转；\n飘零零，二月风筝线儿断。",
        "智者乐山山如画，\n仁者乐水水无涯。\n从从容容一杯酒，\n平平淡淡一杯茶。\n\n细雨朦胧小石桥，\n春风荡漾小竹筏。\n夜无明月花独舞，\n腹有诗书气自华。",
        "浮名浮利，虚苦劳神。\n叹隙中驹，石中火，梦中身。\n几时归去，作个闲人。\n\n网事已成空，还如一梦中。\n相见争如不见，有情还是无情。\n今朝有酒今朝醉，管他对错是与非。",
      ];
      $idx = mt_rand(0, count($poems)-1);
      $result = $poems[$idx];
      $result.= "\n\n还不解聊？去调戏度娘吧：“度娘，<a http=\"https://www.baidu.com/s?wd={$keyword}\">{$keyword}</a>”";
    }
    elseif (preg_match('/有什么/', $keyword)) {
      $result = '你好，非常感谢你对小蜜的关注，我们近期将会上线产品模块，敬请留意！';
    }
    elseif (preg_match('/^http(s)?:\/\//i', $keyword)) {
      $result = "请访问: <a http=\"{$keyword}\">{$keyword}</a>";
    }
    elseif(in_array($keyword, array('?','？','阿','啊','在','在?','在？','哈哈','呵呵','哈','呵','哼'))
      || is_numeric($keyword)
      || preg_match("!^/:!", $keyword) //表情
      || preg_match("/(在吗|你好|您好|哈哈|呵呵|哈|呵|哼|hi|hello|hallo)/i", $keyword)
    ){
      $result = $this->defaultHello();
    }
    else { //查询数据库
      /*
      import('node/Node_Model','mobiles');
      $page_size = 10;
      $recordes = Node_Model::searchNode($keyword,$page_size);
      if(empty($recordes)){
        $recordes = Node_Model::searchNode($keyword,$page_size, 1);
      }

      $nodes = [];
      foreach($recordes as $v){
        $record = Node_Model::getNodeInfo($v['nid']);
        $nodes[] = $record;
      }
      $result = $nodes;
      */
    }
    return $result;
  }

  /**
   * 语音关键词查询事件
   *
   * @param string $keyword
   * @param string $openid
   * @param integer $reqtime
   */
  public function onVoiceQuery($keyword, $openid, $reqtime) {
    return $this->onTextQuery($keyword, $openid, $reqtime);
  }

  /**
   * 默认招呼语
   */
  private function defaultHello() {
    $now_h  = intval(date('G'));
    $hello  = '';
    if ($now_h < 5) {
      $hello = '您好，凌晨了';
    }
    elseif ($now_h < 11) {
      $hello = '早上好';
    }
    elseif ($now_h < 14) {
      $hello = '中午好';
    }
    elseif ($now_h < 19) {
      $hello = '下午好';
    }
    else {
      $hello = '晚上好';
    }
    return $hello.'！请问有什么可以帮到您？';
  }
  
  /**
   * "关于小蜜" 的返回文字
   * 
   * @param int $type
   * @return string
   */
  public function about($type = 0) {
    $ext = $ext2 = '';
    if (1==$type) {
      //$ext = "祝你五一好心情，玩得开心哟，参加滦河马拉松赛事的话预祝你取得好成绩！/:,@-D\n\n";
      $ext2= "，小蜜还会定期为你发布一些可能会对你有用的资讯文章";
    }
    elseif (2==$type) {
      $text = "你好，我是福小蜜/::)\n\n福小蜜海外购，专注于海外商品的代购，让你足不出户即可享受来自澳洲、新西兰、加拿大等海外的放心商品。\n\n觉得小蜜还行的话就帮忙向好友推荐一下吧，这是小蜜的公众号：fxmgou，\n谢谢/::*";
      return $text;
    }
    $text = "你好，欢迎关注小蜜/:rose\n\n{$ext}你可以将底部菜单切换到回复模式跟小蜜文字或语音对话，希望能给你带来点小惊喜/::$\n\n福小蜜海外购，让你足不出户即可享受来自澳洲、新西兰、加拿大等海外的放心商品{$ext2}。\n\n觉得小蜜还行的话就帮忙向好友推荐一下吧，这是小蜜的公众号：fxmgou，\n谢谢/::*";
    return $text;
  }
  
  /**
   * "最新文章" 的返回文字
   * @return string
   */
  public function latestArticles() {
    $article_set = [
      ['title'=>'防晒 | BananaBoat香蕉船', 'url'=>'http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=205180249&idx=1&sn=c9fa26f2bf102a92312bcdc7492ccca0#rd'],
      ['title'=>'Banana Boat 香蕉船运动型防晒霜 SPF50+ 200g', 'url'=>'http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=205180249&idx=2&sn=59dfe537e6b981939c654b754cf8b438#rd'],
      ['title'=>'Swisse护肝排毒片120片', 'url'=>'http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=204971042&idx=1&sn=45609e9aeef25644ff2e798ab0c67c8c#rd'],
      ['title'=>'女神养成三部曲', 'http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=204970269&idx=1&sn=de7a6eaba3e87891bc66858af6cb9cbe#rd'],
      ['title'=>'澳大利亚保健品市场本土三大品牌Blackmores、Swisse、Bio island', 'url'=>'http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=204965844&idx=1&sn=4158ba58d80ada011707fa4e28c27079#rd']
    ];
    
    $text = "最新文章";
    $i = 1;
    foreach($article_set AS $_url) {
      $text.= "\n\n{$i}、<a href=\"{$_url['url']}\">{$_url['title']}</a>";
      $i++;
    }
    
    return $text;
  }

  /**
   * 获取access token之前的检查事件
   *
   * @param string $type, optional value: 'basic':基本型; 'oauth':OAuth2型; 'jsapi': jsapi ticket
   * @param string $appId,
   * @param string $openId,
   * @return string
   */
  public function onFetchAccessTokenBefore($type, $appId, $openId = '') {
    $token = '';
    $now   = time();
    if ('basic'==$type) {
      $token = D()->result("SELECT `access_token` FROM `{access_token_weixin}` WHERE `type`='basic' AND `appid`='%s' AND `expires_at`>{$now} ORDER BY `rid` DESC LIMIT 1", $appId);
    }
    elseif ('oauth'==$type) {
      $token = D()->result("SELECT `access_token` FROM `{access_token_weixin}` WHERE `type`='oauth' AND `appid`='%s' AND `openid`='%s' AND `expires_at`>{$now} ORDER BY `rid` DESC LIMIT 1", $appId, $openId);
    }
    elseif ('jsapi'==$type) {
      $token = D()->result("SELECT `access_token` FROM `{access_token_weixin}` WHERE `type`='jsapi' AND `appid`='%s' AND `expires_at`>{$now} ORDER BY `rid` DESC LIMIT 1", $appId);
    }
    return $token;
  }

  /**
   * 获取access token成功事件
   *
   * @param string $access_token
   * @param integer $expires_in
   * @param string $type, optional value: 'basic':基本型; 'oauth':OAuth2型; 'jsapi': jsapi ticket
   * @param string $appId,
   * @param string $openid,
   * @param array $retdata, 微信服务器返回的数据
   */
  public function onFetchAccessTokenSuccess($type, $appId, $openid = '', Array $retdata = array()) {
    $now  = time();
    $data = array(
      'type'         => $type,
      'access_token' => 'jsapi'==$type ? $retdata['ticket'] : $retdata['access_token'],
      'expires_at'   => $now + $retdata['expires_in'] - 10, //减10是为了避免网络误差时间
      'appid'        => $appId,
      'openid'       => isset($retdata['openid']) ? $retdata['openid'] : $openid,
      'refresh_token'=> isset($retdata['refresh_token']) ? $retdata['refresh_token'] : '',
      'scope'        => isset($retdata['scope']) ? $retdata['scope'] : '',
      'timeline'     => $now,
    );
    $rid = D()->insert('access_token_weixin', $data);
    return $rid;
  }

}


 
/*----- END FILE: class.Weixin.php -----*/