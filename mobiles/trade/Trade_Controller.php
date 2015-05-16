<?php
/**
 * 购物流程控制器
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Trade_Controller extends Controller {
  
  private $nav_no     = 2;       //主导航id
  private $topnav_no  = 0;       //顶部导航id
  private $nav_flag1  = 'cart';  //导航标识1
  private $nav_flag2  = '';      //导航标识2
  private $nav_flag3  = '';      //导航标识3
  
  /**
   * hook init
   *
   * @param string $action
   * @param Request $request
   * @param Response $response
   */
  public function init($action, Request $request, Response $response)
  {
    if (!$request->is_post()) {
      $this->v = new PageView();
      $this->v->add_render_filter(function(View $v){
        $v->assign('nav_no',     $this->nav_no)
          ->assign('topnav_no',  $this->topnav_no)
          ->assign('nav_flag1',  $this->nav_flag1)
          ->assign('nav_flag2',  $this->nav_flag2)
          ->assign('nav_flag3',  $this->nav_flag3);
      });
      $this->v->assign('no_display_cart', true);
    }
  }
  
  /**
   * hook menu
   * @see Controller::menu()
   */
  public function menu()
  {
    return [
      'trade/cart/add'    => 'cart_add',
      'trade/cart/list'   => 'cart_list',
      'trade/cart/delete' => 'cart_delete',
      'trade/cart/chgnum' => 'cart_chgnum',
      'trade/order/confirm'  => 'order_confirm',
      'trade/order/submit'   => 'order_submit',
      'trade/order/upaddress'=> 'order_upaddress',
    ];
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
   * 添加购物车
   *
   * @param Request $request
   * @param Response $response
   */
  public function cart_add(Request $request, Response $response)
  {
    if ($request->is_post()) {
      $ret = ['flag'=>'FAIL','msg'=>'添加失败'];
      $goods_id = $request->post('goods_id',0);
      $goods_num= $request->post('goods_num',1);
      $ret = Goods::addToCart($goods_id, $goods_num);
      if ($ret['code']>0) {
        $ret['cart_num'] = Goods::getUserCartNum($user_id);
      }
      $response->sendJSON($ret);
    }
  }
  
  /**
   * 删除购物车中的商品
   *
   * @param Request $request
   * @param Response $response
   */
  public function cart_delete(Request $request, Response $response)
  {
    if ($request->is_post()) {
      $ret = ['flag'=>'FAIL','msg'=>'删除失败'];
      $rec_ids = $request->post('rec_id',[]);
      
      if(empty($rec_ids)) {
        $ret['msg'] = '没有要删的记录';
        $response->sendJSON($ret);
      }
      
      $user_id = $GLOBALS['user']->ec_user_id;
      if (!$user_id) {
        $ret['msg'] = '请先登录';
        $response->sendJSON($ret);
      }
      
      $ret = Goods::deleteCartGoods($rec_ids, $user_id);
      if ($ret['code']>0) {
        $ret['flag'] = 'SUC';
        $ret['rec_ids'] = $rec_ids;
      }
      $response->sendJSON($ret);
    }
  }
  
  /**
   * 改变购物车中的商品选购数量
   *
   * @param Request $request
   * @param Response $response
   */
  public function cart_chgnum(Request $request, Response $response)
  {
    if ($request->is_post()) {
      $ret = ['flag'=>'FAIL','msg'=>'更改失败'];
      $rec_ids = $request->post('rec_id',[]);
      $gnums   = $request->post('gnum',[]);
      
      if(empty($rec_ids)) {
        $ret['msg'] = '没有要更改的记录';
        $response->sendJSON($ret);
      }
      
      $user_id = $GLOBALS['user']->ec_user_id;
      if (!$user_id) {
        $ret['msg'] = '请先登录';
        $response->sendJSON($ret);
      }
      
      $i = 0;
      $succ_rids = [];
      foreach ($rec_ids AS $rid) {
        if (Goods::changeCartGoodsNum($user_id, $rid, $gnums[$i], true, true)) {
          $succ_rids[] = $rid;
        }
        ++$i;
      }
      
      
      if (count($succ_rids)>0) {
        $ret['flag'] = 'SUC';
        $ret['succ_rids'] = $succ_rids;
      }
      $response->sendJSON($ret);
    }
  }
  
  /**
   * 添加购物车
   *
   * @param Request $request
   * @param Response $response
   */
  public function cart_list(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_trade_cart_list');
    $this->nav_flag2 = 'cartlist';
    $this->topnav_no = 1; // >0: 表示有topnav bar，具体值标识哪个topnav bar(有多个的情况下)
    if ($request->is_hashreq()) {
      $user_id  = $GLOBALS['user']->ec_user_id;
      if (!$user_id) $user_id = session_id(); 
      $cartGoods = Goods::getUserCart($user_id);
      $cartNum   = Goods::getUserCartNum($user_id);
      $this->v->assign('cartGoods', $cartGoods);
      $this->v->assign('cartNum', intval($cartNum));
      $this->v->assign('cartRecNum', count($cartGoods));
    }
    else {
      
    }
    $response->send($this->v);
  }
  
  /**
   * 购买记录
   *
   * @param Request $request
   * @param Response $response
   */
  public function buyrecord(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_trade_buyrecord');
    $this->nav_flag2 = 'buyrecord';
    $this->nav_no    = 0;
    $this->topnav_no = 1; // >0: 表示有topnav bar，具体值标识哪个topnav bar(有多个的情况下)
    if ($request->is_hashreq()) {
      
    }
    else {
      
    }
    $response->send($this->v);
  }
  
  /**
   * 订单确认
   *
   * @param Request $request
   * @param Response $response
   */
  public function order_confirm(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_trade_order_confirm');
    $this->nav_flag1 = 'order';
    $this->nav_flag2 = 'order_confirm';
    $this->nav_no    = 0;
    if ($request->is_hashreq()) {
      $cart_rids = $request->get('cart_rids','');
      $timestamp = $request->get('t',0);
      $cart_rids = trim($cart_rids);
      
      $errmsg = '';
      $this->v->add_render_filter(function(View $v) use(&$errmsg){
        $v->assign('errmsg', $errmsg);
      });
      
      $now = simphp_time();
      $diff= abs($now-$timestamp);
      $this->v->assign('diff', $diff);
      if ( $diff > 60*150000) { //误差不能超过15分钟，否则判无效请求
        $errmsg = "无效请求";
        $response->send($this->v);
      }
      
      if (''==$cart_rids || !preg_match('/^(\d)+[,\d ]*$/', $cart_rids)) {
        $errmsg = "结账商品为空";
        $response->send($this->v);
      }
      
      $cart_rids = explode(',', $cart_rids);
      foreach ($cart_rids AS &$rid) {
        $rid = trim($rid);
      }
      
      //订单商品信息
      $order_goods = Goods::getOrderGoods($cart_rids, null, $total_price);
      $this->v->assign('order_goods', $order_goods);
      $this->v->assign('order_goods_num', count($order_goods));
      $this->v->assign('total_price', $total_price);
      
      //搜索地址
      $ec_user_id = $GLOBALS['user']->ec_user_id;
      $user_addrs = Goods::getUserAddress($ec_user_id);
      $this->v->assign('user_addrs', $user_addrs);
      $this->v->assign('user_addrs_num', count($user_addrs));
      
    }
    else {
      $code = $request->get('code', '');
      if (''!=$code) { //微信base授权
        
        $state = $request->get('state', '');
        
        //授权出错
        if (!in_array($state, array('base','detail'))) {
          Fn::showErrorMessage('授权出错，提交订单失败！', true);
        }
        
        $wx = new Weixin(['wxpay']);
        
        //用code换取access token
        $code_ret = $wx->request_access_token($code);
        if (!empty($code_ret['errcode'])) {
          Fn::showErrorMessage('微信授权错误<br/>'.$code_ret['errcode'].'('.$code_ret['errmsg'].')', true);
        }
        
        $accessToken = $code_ret['access_token'];
        $wxAddrJs = $wx->wxpay->addrJs($accessToken);
        $this->v->add_append_filter(function(PageView $v) use($wxAddrJs) {
          $v->append_to_foot_js .= $wxAddrJs;
        },'foot');
        
      }
      else { //正常访问
        if (Weixin::isWeixinBrowser()) {
          (new Weixin())->authorizing($request->url(), 'base'); //base授权获取access token以便于操作收货地址
          //(new Weixin())->authorizing('http://'.$request->host().'/user/oauth/weixin?act=jsapi_address&refer='.urlencode($request->url()), 'base'); //base授权获取access token以便于操作收货地址
        }
      }
    }
    $response->send($this->v);
  }
  
  /**
   * 订单确认
   *
   * @param Request $request
   * @param Response $response
   */
  public function order_submit(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_trade_order_submit');
    $this->nav_flag1 = 'order';
    $this->nav_flag2 = 'order_submit';
    $this->nav_no    = 0;
    if ($request->is_hashreq()) {
      
    }
    else {
      
    }
    $response->send($this->v);
  }
  

  /**
   * 更新收货地址
   *
   * @param Request $request
   * @param Response $response
   */
  public function order_upaddress(Request $request, Response $response)
  {
    if ($request->is_post()) {
      $ret = ['flag'=>'FAIL','msg'=>'更新失败'];
      trace_debug('order_upaddress', $_POST);
      
      $ec_user_id = $GLOBALS['user']->ec_user_id;
      if (!$ec_user_id) {
        $ret['msg'] = '未登录, 请登录';
        $response->sendJSON($ret);
      }
      
      $address_id    = $request->post('address_id', 0);
      $consignee     = $request->post('consignee', '');
      $contact_phone = $request->post('contact_phone', '');
      $country       = $request->post('country', 1);
      $country_name  = $request->post('country_name', '中国');
      $province      = $request->post('province', 0);
      $province_name = $request->post('province_name', '');
      $city          = $request->post('city', 0);
      $city_name     = $request->post('city_name', '');
      $district      = $request->post('district', 0);
      $district_name = $request->post('district_name', '');
      $address       = $request->post('address', '');
      $zipcode       = $request->post('zipcode', '');
      
      $address_id = intval($address_id);
      $data = [
        'user_id'       => $ec_user_id,
        'consignee'     => $consignee,
        'country'       => $country,
        'country_name'  => $country_name,
        'province'      => $province,
        'province_name' => $province_name,
        'city'          => $city,
        'city_name'     => $city_name,
        'district'      => $district,
        'district_name' => $district_name,
        'address'       => $address,
        'zipcode'       => $zipcode,
      ];
      if (preg_match('/^1\d{10}$/', $contact_phone)) { //是手机号
        $data['mobile'] = $contact_phone;
      }
      else {
        $data['tel'] = $contact_phone;
      }
      
      $address_id = Goods::saveUserAddress($data, $address_id);
      $ret = ['flag'=>'SUC','msg'=>'更新成功','address_id'=>$address_id];
      
      $response->sendJSON($ret);
    }
  }
  
}
 
/*----- END FILE: Trade_Controller.php -----*/