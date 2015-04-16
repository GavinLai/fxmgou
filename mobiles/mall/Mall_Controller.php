<?php
/**
 * Mall Controller
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Mall_Controller extends Controller {
  
  private $_nav_no     = 1;
  private $_nav        = 'mall';
  private $_nav_second = '';
  
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
    $this->v->set_tplname('mod_mall_index');
    if ($request->is_hashreq()) {
      //猜你喜欢，按喜欢人数前5
      import('node/Node_Model');
      $page_size = 5;
      $likeList = Node_Model::getNodeList(0, 'gift', 'votecnt', 'DESC', 0, $page_size);
      $this->v->assign('likeList', $likeList);

      $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
      $total_page = $GLOBALS['pager_totalpage_arr'][0];

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);
    }
    $response->send($this->v);
  }

  /**
   * 商品详情页
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function detail(Request $request, Response $response){
    $this->v->assign('nav_no', 5);

    $user = Member::getUser();
    $uid = $user['uid'];

    $nid = $request->arg(2);

    $this->v->set_tplname('mod_mall_detail');
    if ($request->is_hashreq()) {
     
      $goods_info = Mall_Model::goodsInfo($nid);
      $this->v->assign('goods', $goods_info);

      import('node/Node_Model');
      $collect = Node_Model::actionRecord($nid, $uid, 'collect');
    
      $this->v->assign('collect',($collect ? '1':'0'));
    }


    $response->send($this->v);
  }

  public function buy(Request $request, Response $response){
    $this->v->assign('nav_no', 6);
    $nid = $request->arg(2);

    $this->v->set_tplname('mod_mall_order');
    if ($request->is_hashreq()) {
      import('node/Node_Model');
      $nodeInfo  = Node_Model::getNodeInfo($nid);

      $this->v->assign('nodeInfo', $nodeInfo);
    }

    $response->send($this->v); 
  }

  public function order(Request $request, response $response){
    $res = ['flag'=>'FAIL', 'data'=>'', 'msg'=>''];
    if(!Member::isLogined()){
      $res['msg'] = '请先登录';
      $response->sendJSON($res);
    }

    $user = Member::getUser();
    $uid = $user['uid'];
    $nid = (int)$request->post('nid',0);
    $num = (int)$request->post('num',0);
    $consignee_name = $request->post('consignee_name', '');
    $consignee_mobile = $request->post('consignee_mobile', '');
    $consignee_addr = $request->post('consignee_addr', '');
    $pay_type = $request->post('pay_type','');

    import('node/Node_Model');
    $nodeInfo  = Node_Model::getNodeInfo($nid); 
    if(empty($nodeInfo)){
      $res['msg'] = '商品已下架';
      $response->sendJson($res);
    }
    if($num<1){
      $res['msg'] = '商品数量错误';
      $response->sendJson($res); 
    }
    if($nodeInfo['count']<$num){
      $res['msg'] = '商品库存不足';
      $response->sendJson($res);  
    }
    if($consignee_addr==''){
      $res['msg'] = '请输入收货地址';
      $response->sendJson($res);
    }
    if(!in_array($pay_type, ['wxpay', 'alipay'])){
      $res['msg'] = '未知的支付方式';
      $response->sendJson($res);
    }

    $orderno = str_replace("-","",date("Y-m-dH-i-s")).rand(1000,9999);//生成订单号,18位;
    $order_data = [
      'order_no'  => $orderno,
      'uid'       => $uid,
      'goods_id'  => $nid,
      'goods_name'=> $nodeInfo['title'],
      'goods_img' => $nodeInfo['goods_url'],
      'goods_price'      => $nodeInfo['goods_price'],
      'goods_num' => $num,
      'goods_total'      => $nodeInfo['goods_price']*$num,
      'consignee_name'   => $consignee_name,
      'consignee_mobile' => $consignee_mobile,
      'consignee_addr' => $consignee_addr,
      'pay_type'  => $pay_type,
      'user_ip'   => $request->ip(),
      'timeline'  => time()
    ];

    $oid = Mall_Model::saveOrder($order_data);
    if($oid<1){
      $res['msg'] = '系统繁忙，请稍后再试';
      $response->sendJson($res);
    }else{
      //更新商品数量
      $affected = Node_Model::updateGiftCount($nid, $num);
      if(!$affected){
        $res['msg'] = '系统繁忙，请稍后再试';
        $response->sendJson($res);
      }
    }
    
    $res['data'] = Pay::pay_gateway($pay_type, $orderno, $order_data['goods_total'], $order_data['goods_name']);


    $res['flag'] = 'SUC';
    $response->sendJson($res);
  }
}