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
	public static $allowAccount = array('fxm','zfy','lyl');
	
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
	 * API address url prefix
	 * @var array
	 */
	public static $apiUrlPrefix = array(
	  'api_cgi'   => 'https://api.weixin.qq.com/cgi-bin',
	  'api_sns'   => 'https://api.weixin.qq.com/sns',
	  'open_conn' => 'https://open.weixin.qq.com/connect',
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
	 * @param string $target, 目标平台，可选值：'','fxm','zfy'
	 */
	private function init($target = '')
	{
	  $target = ''==$target ? 'fxm' : $target;
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
	}
	
	/**
	 * 构造函数
	 * @param string $target, 目标平台，可选值：'','fxm','zfy'
	 */
	public function __construct($target = '')
	{
		$this->init($target); //该句必须出现在所有对外方法的最开始
	}
	
	/**
	 * 验证接口地址有效性
	 */
	public function valid()
	{
	  $echoStr = $_GET["echostr"];
    if($this->checkSignature()){
      echo $echoStr;
    }
    else {
      echo "";
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
        $base_url = 'http://'.Config::get('env.site.mobile');
        switch ($postObj->EventKey) {
          case '100':
            break;
          case '201'://文字
            $page_size = 5;
            $data = $this->helper->getNode('word',$page_size);
            //msgTpl
            foreach($data as $val){
              $contentText .= sprintf(self::$msgTpl['news_item'], "\n".$val['content'], $val['content'], '', $base_url."/node/{$val['nid']}/edit");
            }
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data), $contentText);
            return $responseText;
            break;
          case '202'://贺卡
            $page_size = 5;
            $data = $this->helper->getNode('card',$page_size);
            //msgTpl
            foreach($data as $val){
              $title = $val['content']=='' ? $val['title']:$val['content'];
              $picUrl = $val['cover_url'] ? $val['cover_url'] : $val['card_url'];
              $picUrl = preg_match("!^(http|https):\/\/!i", $picUrl) ? $picUrl : $base_url.$picUrl;
              $contentText .= sprintf(self::$msgTpl['news_item'], $title, $title, $picUrl, $base_url."/node/{$val['nid']}/edit");
            }
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data),$contentText);
            return $responseText;
            break;
          case '203'://音乐
            $page_size = 5;
            $data = $this->helper->getNode('music',$page_size);
            //msgTpl
            foreach($data as $val){
              $picUrl = $val['icon_url'] ? $val['icon_url'] : $val['bg_url'];
              $picUrl = preg_match("!^(http|https):\/\/!i", $picUrl) ? $picUrl : $base_url.$picUrl;
              $contentText .= sprintf(self::$msgTpl['news_item'], $val['title'].' - '.$val['singer_name'], $val['title'].' - '.$val['singer_name'], $picUrl, $base_url."/node/{$val['nid']}/edit");
            }
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data),$contentText);
            return $responseText;
            break;
          case '204'://礼物
            $page_size = 5;
            $data = $this->helper->getNode('gift',$page_size);
            //msgTpl
          
            foreach($data as $val){
              $contentText .= sprintf(self::$msgTpl['news_item'], $val['title'], strip_tags($val['desc']), preg_match("!^(http|https):\/\/!i", $val['goods_url']) ? $val['goods_url'] : $base_url.$val['goods_url'], $base_url."/mall/detail/{$val['nid']}");
            }
          
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data),$contentText);
            return $responseText;
            break;

          case '205'://随机
            $page_size = 5;
            $data = $this->helper->getNode('random',$page_size);
            //msgTpl
            foreach($data as $val){
              switch ($val['type_id']) {
                case 'word':
                  $title = "\n".$val['content'];
                  $desc = $val['content'];
                  //$pic = $base_url.'/misc/images/napp/touch-icon-256.png';
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
              $contentText .= sprintf(self::$msgTpl['news_item'], $title, $desc, $pic, $link);
            }
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data),$contentText);
            return $responseText;
            break;

          case '301'://活动
            $page_size = 3;
            $data = $this->helper->getActivity($page_size);
            
            foreach($data as $val){
              $title = $val['title'];
              $desc = strip_tags($val['content']);
              $pic = preg_match("!^(http|https):\/\/!i", $val['img']) ? $val['img'] : $base_url.$val['img'];
              $link = $base_url.'/activity/detail/'.$val['aid'];
              $contentText .= sprintf(self::$msgTpl['news_item'], $title, $desc, $pic, $link);
            }
            
            $responseText= sprintf(self::$msgTpl['news'], $fromUsername, $toUsername, $restime, count($data),$contentText);
            return $responseText;
            break;
          case '302'://如何玩?  
          $contentText = '如何玩?  ';
          $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime,$contentText);
          return $responseText;
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
    $contentText  = $this->helper->onTextQuery($keyword, $openid, $reqtime);
    $responseText = '';
    if (!empty($contentText)&&!is_array($contentText)) {
      $responseText= sprintf(self::$msgTpl['text'], $fromUsername, $toUsername, $restime, $contentText);
    }elseif(!empty($contentText)&&is_array($contentText)){
      //msgTpl
      $text = '';
      $base_url = 'http://'.Config::get('env.site.mobile');
      foreach($contentText as $val){
        switch ($val['type_id']) {
          case 'word':
            $title = "\n".$val['content'];
            $desc = $val['content'];
            //$pic = $base_url.'/misc/images/napp/touch-icon-256.png';
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
    }else{
      $contentText = '很抱歉，没有找到您想要的东西！';
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
    if (''==$keyword) return '';
    $fromUsername = $postObj->FromUserName;
    $toUsername   = $postObj->ToUserName;
    $openid       = $fromUsername;
    $reqtime      = intval($postObj->CreateTime);
    $restime      = time();
    $contentText  = $this->helper->onVoiceQuery($keyword, $openid, $reqtime);
    $responseText = '';
    if (!empty($contentText)) {
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
  private function apiCall($uri_path, $params=array(), $method='get', $type='api_cgi')
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
        $udata['auth_method'] = 'native';
      }
      if (!Member::checkExistByOpenid($openid, $from)) { //用户不存在
        Member::createUser($udata, $from);
      }
      else { //用户已存在
        unset($udata['openid'],$udata['auth_method']);
        Member::updateUser($udata, $openid, $from);
      }
    }
    /*
    $base_url    = 'http://'.Config::get('env.site.mobile');
    $hds = $this->getActivity();
    $contentText = '';
    foreach($hds as $val){
      $title = $val['title'];
      $desc = strip_tags($val['content']);
      $pic = preg_match("!^(http|https):\/\/!i", $val['img']) ? $val['img'] : $base_url.$val['img'];
      $link = $base_url.'/activity/detail/'.$val['aid'];
      $contentText .= sprintf(Weixin::$msgTpl['news_item'], $title, $desc, $pic, $link);
    }
    $restime = time();
    $msg= sprintf(Weixin::$msgTpl['news'], $openid, $toUsername, $restime, count($hds),$contentText);
    echo $msg;
    exit;
    */
    $url = C('env.site.mobile');
    $msg = "嗨！您好，欢迎您到来/:rose
福小秘是祝福分享平台，
我有文字音乐贺卡礼物，
让您时刻体验祝福乐趣，
将祝福准确传递给对方。
· 指尖 · 心头 · 脑海中 ·
念念不忘 -- 福小秘！\n<a href=\"http://{$url}/\">点击这里进入应用>></a>";
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
    if(in_array($keyword, array('?','？','阿','啊','在','在?','在？'))
      || is_numeric($keyword)
      || preg_match("!^/:!", $keyword) //表情
      || preg_match("/(hi|hello|在吗|你好|您好)/i", $keyword)
    ){
      $result = $this->defaultHello();
    }else{
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
   * 获取access token之前的检查事件
   *
   * @param string $type, optional value: 'basic':基本型; 'oauth':OAuth2型
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
    return $token;
  }

  /**
   * 获取access token成功事件
   *
   * @param string $access_token
   * @param integer $expires_in
   * @param string $type, optional value: 'basic':基本型; 'oauth':OAuth2型
   * @param string $appId,
   * @param string $openid,
   * @param array $retdata, 微信服务器返回的数据
   */
  public function onFetchAccessTokenSuccess($type, $appId, $openid = '', Array $retdata = array()) {
    $now  = time();
    $data = array(
      'type'         => $type,
      'access_token' => $retdata['access_token'],
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

  /**
   * 获取
   * @param  [type]  $type      word,card,music,gift,random
   * @param  integer $page_size [description]
   * @return [type]             [description]
   */
  public function getNode($type,$page_size=1){
    if(!in_array($type, ['word','card','music','gift','random'])){
      return '';
    }

    $records = [];
    switch ($type) {
      case 'word':
        $sql = "SELECT * FROM {node} WHERE status='R' AND recommend<>0 AND type_id='word' ORDER BY recommend DESC LIMIT 0,{$page_size}";
        $records = D()->query($sql)->fetch_array_all();
        if(empty($records)){
          $sql = "SELECT * FROM {node} WHERE status='R' AND type_id='word'  ORDER BY changed DESC LIMIT 0,{$page_size}";
          $records = D()->query($sql)->fetch_array_all();
        }
        break;
      case 'card':
        $sql = "SELECT n.*,c.* FROM {node} n ,{node_card} c WHERE n.status='R'  AND n.recommend<>0 AND type_id='card'  AND n.nid=c.tnid ORDER BY n.recommend DESC LIMIT 0,{$page_size}";
        $records = D()->query($sql)->fetch_array_all();
        if(empty($records)){
          $sql = "SELECT n.*,c.* FROM {node} n ,{node_card} c WHERE n.status='R' AND type_id='card'  AND n.nid=c.tnid ORDER BY n.changed DESC LIMIT 0,{$page_size}";
          $records = D()->query($sql)->fetch_array_all();
        }
        break;
      case 'music':
        $sql = "SELECT n.*,m.* FROM {node} n ,{node_music} m WHERE n.status='R'  AND n.recommend<>0 AND type_id='music' AND n.nid=m.tnid ORDER BY n.recommend DESC LIMIT 0,{$page_size}";
        $records = D()->query($sql)->fetch_array_all();
        if(empty($records)){
          $sql = "SELECT n.*,c.* FROM {node} n ,{node_card} m WHERE n.status='R' AND type_id='music'  AND n.nid=c.tnid ORDER BY n.changed DESC LIMIT 0,{$page_size}";
          $records = D()->query($sql)->fetch_array_all();
        }
        break;
      case 'gift':
        $sql = "SELECT n.*,g.* FROM {node} n ,{node_gift} g WHERE n.status='R'  AND n.recommend<>0  AND type_id='gift' AND n.nid=g.tnid ORDER BY n.recommend DESC LIMIT 0,{$page_size}";
        $records = D()->query($sql)->fetch_array_all();
        if(empty($records)){
          $sql = "SELECT n.*,g.* FROM {node} n ,{node_gift} g WHERE n.status='R' AND type_id='gift'  AND n.nid=g.tnid ORDER BY n.changed DESC LIMIT 0,{$page_size}";
          $records = D()->query($sql)->fetch_array_all();
        }
        break;
      case 'random':
        //文字
        $sql = "SELECT * FROM {node} WHERE status='R' AND recommend<>0 AND  type_id='word' ORDER BY rand() DESC LIMIT 1 ";
        $record = D()->get_one($sql); 
        if(!empty($record)){
          $records[] = $record;
        }
        //贺卡
        $sql = "SELECT n.*,ne.* FROM {node} n,{node_card} ne WHERE status='R' AND n.recommend<>0 AND type_id='card' AND n.nid=ne.tnid  ORDER BY rand() DESC LIMIT 1 ";
        $record = D()->get_one($sql); 
        if(!empty($record)){
          $records[] = $record;
        }
        //音乐
        $sql = "SELECT n.*,ne.* FROM {node} n,{node_music} ne WHERE status='R' AND n.recommend<>0 AND type_id='music' AND n.nid=ne.tnid  ORDER BY rand() DESC LIMIT 1 ";
        $record = D()->get_one($sql); 
        if(!empty($record)){
          $records[] = $record;
        }
        //礼物
        $sql = "SELECT n.*,ne.* FROM {node} n,{node_gift} ne WHERE status='R' AND n.recommend<>0 AND type_id='gift' AND n.nid=ne.tnid  ORDER BY rand() DESC LIMIT 1 ";
        $record = D()->get_one($sql); 
        if(!empty($record)){
          $records[] = $record;
        }
        break;
      default:
        # code...
        break;
    }

    return $records;
  }
  public function getActivity($page_size=1){
    $now = simphp_time();
    $sql = "SELECT * FROM {activity} WHERE `status`='R' AND (`end_time`>{$now} OR `end_time`=0) ORDER BY changed DESC LIMIT 0,{$page_size}";
    $records = D()->query($sql)->fetch_array_all();
    return $records;
  }

}


 
/*----- END FILE: class.Weixin.php -----*/