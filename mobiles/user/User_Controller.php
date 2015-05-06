<?php
/**
 * Mall Controller
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class User_Controller extends Controller {
  
  private $_nav_no     = 1;
  private $_nav        = 'user';
  private $_nav_second = '';
  
  public function menu() {
    return [
      'user' => 'index',
      'user/oauth/%s' => 'oauth',
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
    $this->v = new PageView();
    $this->v->assign('nav_no',     $this->_nav_no)
            ->assign('nav',        $this->_nav)
            ->assign('nav_second', $this->_nav_second);
  }
  
  /**
   * default action 'index'
   *
   * @param Request $request
   * @param Response $response
   */
  public function index(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_user_index');
    $userInfo = Member::getTinyInfoByUid($GLOBALS['user']->uid);
    
    if ($request->is_hashreq()) {
      $this->v->assign('userInfo', $userInfo);
    }
    else {
      //检查用户信息完成度，nickname或logo没有的话都重定向请求OAuth2详细认证获取资料
      User_Model::checkUserInfoCompleteDegree($userInfo, '/user');
    }
    
    $response->send($this->v);
  }

  /**
   * 随机生成内容
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function find(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_user_find');
    if ($request->is_hashreq()) {
      $uid = $GLOBALS['user']->uid;
      //随机获取10条记录
      import('node/Node_Model');
      $recordes = Node_Model::getRandNode(8);
      $nodes = [];
      foreach($recordes as $v){
        $record = Node_Model::getNodeInfo($v['nid']);
        $record['collect'] = Node_Model::actionRecord($v['nid'], $uid, 'collect');
        $record['love'] = Node_Model::actionRecord($v['nid'], $uid, 'love');
        $nodes[] = $record;
      }
      $this->v->assign('nodes', $nodes);
    }
    $response->send($this->v);
  }
  
  /**
   * [feedback description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function feedback(Request $request, Response $response){
    $this->v->set_tplname('mod_user_feedback');
    if ($request->is_hashreq()) {
      
    }
    $response->send($this->v);
  }
  
  /**
   * [saveFeedback description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function saveFeedback(Request $request, Response $response){
    $res = ['flag'=>'FAIL', 'data'=>''];
    $content = $request->post('content', '');
    $contact = $request->post('contact', '');
    if(content==''){
      $res['data'] = '内容不能为空';
    }else{
      $fid = User_Model::saveFeedback(['content'=>$content, 'contact'=> $contact]);
      if($fid>0){
        $res['flag'] = 'SUC';
      }else{
        $res['data']= '系统繁忙，请稍后再试！';
      }
    }
    $response->sendJSON($res);
  }

  /**
   * [notice description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function notice(Request $request, Response $response){
    $this->v->set_tplname('mod_user_notice');
    if ($request->is_hashreq()) {
      //获取最近分享的10条记录
      import('node/Node_Model');
      $page_size = 5;
      $shareHistory = Node_Model::getShareHistory($GLOBALS['user']->uid,$page_size);

      $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
      $total_page = $GLOBALS['pager_totalpage_arr'][0];

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);

      $this->v->assign('share', $shareHistory);
    }
    $this->v->assign('nav_no',0);
    $response->send($this->v);
  }
  
  /**
   * [setup description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function setup(Request $request, Response $response){
    $this->v->set_tplname('mod_user_setup');
    if ($request->is_hashreq()) {
      $userInfo = Member::getTinyInfoByUid($GLOBALS['user']->uid);
      $this->v->assign('user', $userInfo);
    }
    $response->send($this->v);
  }
  
  public function collect(Request $request, Response $response){
    $uid  = $GLOBALS['user']->uid;
    $type = $request->arg(2);
    if(empty($type)||!in_array($type, ['word', 'card', 'music', 'gift'])){
      $type = 'word';
    }

    $this->v->set_tplname('mod_user_collect');
    $this->v->assign('type', $type);

    if ($request->is_hashreq()) {
      $list = User_Model::getCollectByType($uid, $type);
      import('node/Node_Model');
      $category = Node_Model::getCategoryList();
      $cate = [];
      foreach($category as $v){
        $cate[$v['cate_id']] = $v['cate_name'];
      }

      $this->v->assign('list', $list)->assign('cate', $cate);
    }

    $response->send($this->v); 
  }
  
  public function cancleCollect(Request $request, Response $response){
    $res = ['flag'=>'FAIL','data'=>[],'msg'=>''];
    $nid = (int)$request->post('nid', 0);
    $uid = $GLOBALS['user']->uid;
    import('node/Node_Model');
    if(Node_Model::cancleCollect($nid, $uid)){
      $res['flag'] = 'SUC';
    }else{
      $res['msg'] = '请稍后再试';
    }
    $response->sendJSON($res);
  }

  /**
   * 登录
   * 
   * @param Request $request
   * @param Response $response
   */
  public function login(Request $request, Response $response)
  {
    $refer = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(!Member::isLogined()) {
      $token = $request->get('token','');
      if(''!=$token) { //token登录优先，便于测试
        $this->tokenLogin($request, $response);
      }
      elseif(!Weixin::isWeixinBrowser()) { //不是微信内置浏览器
        $this->tips($request, $response);
      }
      else { //先用base方式获取微信OAuth2授权，以便于取得openid
        (new Weixin())->authorizing('http://'.$request->host().'/user/oauth/weixin?dologin=1&refer='.$refer);
      }
    }
    else {
      $response->redirect($refer);
    }
  }
  
  /**
   * action 'oauth', the OAuth callback
   * 
   * @param Request $request
   * @param Response $response
   */
  public function oauth(Request $request, Response $response)
  {
    trace_debug('weixin_oauth2_callback_doing', $_GET);
    $code = $request->get('code', '');
    if (''!=$code) { //授权通过
      $state = $request->get('state', '');
      $refer = $request->get('refer', '/');
      $from  = $request->arg(2);
      if (empty($from)) $from = 'weixin';
      $dologin = $request->get('dologin',0);
      
      //授权出错
      if (!in_array($state, array('base','detail'))) {
        User_Model::showInvalidLogin('授权出错，不能访问应用！');
      }

      $wx = new Weixin();
      
      //用code换取access token
      $code_ret = $wx->request_access_token($code);
      if (!empty($code_ret['errcode'])) {
        User_Model::showInvalidLogin('微信授权错误<br/>'.$code_ret['errcode'].'('.$code_ret['errmsg'].')');
      }
      
      //获取到openid
      $openid = $code_ret['openid'];
      $uid    = 0;
      
      //查询本地是否存在对应openid的用户
      $uinfo_bd = Member::getTinyInfoByOpenid($openid, $from);
      if (!empty($uinfo_bd)) { //用户已存在，对state='base'，则仅需设置登录状态；而对state='detail'，需保存或更新用户数据
        $uid = intval($uinfo_bd['uid']);
        
        if ('detail'===$state) { //detail认证模式，需更新用户数据
          
          $auth_method = 'oauth2_detail';//OAuth2详细认证方式
          
          $uinfo_wx = $wx->userInfoByOAuth2($openid, $code_ret['access_token']);
          if (!empty($uinfo_wx['errcode'])) { //失败！则报错
            User_Model::showInvalidLogin('微信获取用户信息出错！<br/>'.$uinfo_wx['errcode'].'('.$uinfo_wx['errmsg'].')');
          }
          
          //保存微信用户信息到本地库
          $udata = [
            //'openid'   => $openid,
            'unionid'  => isset($uinfo_wx['unionid']) ? $uinfo_wx['unionid'] : '',
            'subscribe'=> isset($uinfo_wx['subscribe']) ? $uinfo_wx['subscribe'] : 0,
            'subscribe_time'=> isset($uinfo_wx['subscribe_time']) ? $uinfo_wx['subscribe_time'] : 0,
            'nickname' => isset($uinfo_wx['nickname']) ? $uinfo_wx['nickname'] : '',
            'logo'     => isset($uinfo_wx['headimgurl']) ? $uinfo_wx['headimgurl'] : '',
            'sex'      => isset($uinfo_wx['sex']) ? $uinfo_wx['sex'] : 0,
            'lang'     => isset($uinfo_wx['language']) ? $uinfo_wx['language'] : '',
            'country'  => isset($uinfo_wx['country']) ? $uinfo_wx['country'] : '',
            'province' => isset($uinfo_wx['province']) ? $uinfo_wx['province'] : '',
            'city'     => isset($uinfo_wx['city']) ? $uinfo_wx['city'] : '',
            'auth_method'=> $auth_method
          ];
          Member::updateUser($udata,$openid,$from);
          
          //尝试用基本型接口获取用户信息，以便确认用户是否已经关注(基本型接口存在 50000000次/日 调用限制，且仅对关注者有效)
          if (!$uinfo_bd['subscribe'] && !$udata['subscribe']) {
            $uinfo_wx = $wx->userInfo($openid);
            //trace_debug('weixin_basic_userinfo', $uinfo_wx);
            if (!empty($uinfo_wx['errcode'])) { //失败！说明很可能没关注，维持现状不处理
              
            }
            else { //成功！说明之前已经关注，得更新关注标记
              $udata = [
                'subscribe'=> isset($uinfo_wx['subscribe']) ? $uinfo_wx['subscribe'] : 0,
                'subscribe_time'=> isset($uinfo_wx['subscribe_time']) ? $uinfo_wx['subscribe_time'] : 0,
              ];
              Member::updateUser($udata,$openid,$from);
            }
          }
          
        } //End: if ('detail'===$state)
        
      }
      else { //用户不存在，则要尝试建立
        
        if ('base'===$state) { //基本授权方式
          
          $auth_method = 'oauth2_base';//基本认证方式
          
          //保存微信用户信息到本地库
          $udata = [
            'openid'     => $openid,
            'auth_method'=> $auth_method
          ];
          $uid = Member::createUser($udata, $from);
          
        }


      } //End: if (!empty($uinfo_bd)) else
      
      //设置本地登录状态
      if ($dologin) {
        
        if (empty($uid)) {
          User_Model::showInvalidLogin('微信授权登录失败！');
        }
        
        Member::setLocalLogin($uid);
      }
      
      //跳转
      $response->redirect($refer);
    }
    else {
      //授权未通过
      User_Model::showInvalidLogin('未授权，不能访问应用！');
    }
  }

  /**
   * 退出登录
   * 
   * @param Request $request
   * @param Response $response
   */
  public function logout(Request $request, Response $response){
    // Unset all of the session variables.
    session_destroy();
    $_SESSION = array();
    
    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (isset($_COOKIE[session_name()])) {
      Cookie::raw_remove(session_name());
    }
    
    // Finally, destroy the session.
    SIMPHP::$session->anonymous_user($GLOBALS['user']);
    
    // Reload current pag
    $response->reload();
  }

  /**
   * token登录(用于测试)
   * 
   * @param Request $request
   * @param Response $response
   */
  public function tokenLogin(Request $request, Response $response){
    
    //1.简单openId登录
    $token = $request->get('token','');
    if(''==$token){
      $this->tips($request, $response);
    }
    
    $openid = User_Model::checkAccessToken($token);
    if($openid === FALSE){
      $this->tips($request, $response);
    }
    
    $userInfo = Member::getTinyInfoByOpenid($openid);
    if(empty($userInfo)){
      User_Model::showInvalidLogin();
    }
    
    //设置本地登录状态
    Member::setLocalLogin($userInfo['uid']);
    
    $refer = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $response->redirect($refer);
    
    exit;
  }
  
  /**
   * tips页显示
   * @param Request $request
   * @param Response $response
   */
  public function tips(Request $request, Response $response){
    $this->v = new PageView('','tips');
    $response->send($this->v);
  }
  
}

/*----- END FILE: User_Controller.php -----*/