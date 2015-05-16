<?php
/**
 * Node Controller
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Node_Controller extends Controller {
  
  private $_nav_no     = 2;
  private $_nav        = '';
  private $_nav_second = '';
  
  public function menu() {
    return [
      'node/cate/%d' => 'cate',
      'node/%d/edit' => 'edit',
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
    $this->v->set_tplname('mod_node_index');
    if ($request->is_hashreq()) {
      
    }
    $response->send($this->v);
  }
  
  /**
   * action 'cate'
   *
   * @param Request $request
   * @param Response $response
   */
  public function cate(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_node_cate');
    if ($request->is_hashreq()) {
      $uid = $_SESSION['uid'];
      $the_cate_id = $request->arg(2);
      if (!$the_cate_id) {
        $the_cate_id = 0;
      }
      $this->v->assign('the_cate_id', $the_cate_id);

      $the_tag_id = $request->arg(3);
      if (!$the_tag_id) {
        $the_tag_id = 0;
      }
      $this->v->assign('the_tag_id', $the_tag_id);
      
      $the_type_id = $request->get('t');
      if (!in_array($the_type_id, ['word','card','music','gift'])) {
        $the_type_id = 'word';
      }
      $this->v->assign('the_type_id', $the_type_id)
              ->assign('nav', $the_type_id)
              ->assign('nav_second', $the_cate_id);
      
      $orderby = $request->get('orderby','created');
      $order   = $request->get('order');
      
      //分类
      $cate_list = Node_Model::getCategoryList();
      $this->v->assign('cate_list', $cate_list);
      
      $the_cate_name = '全部分类';
      foreach($cate_list AS $cate) {
        if ($cate['cate_id']==$the_cate_id) {
          $the_cate_name = $cate['cate_name'];
        }
      }
      $this->v->assign('the_cate_name', $the_cate_name);

      //tag
      $tag_list = Node_Model::getTagByCate($the_cate_id);
      $this->v->assign('tag_list',$tag_list);

      $the_tag_name = '全部';
      foreach($tag_list AS $tag) {
        if ($tag['tag_id']==$the_tag_id) {
          $the_tag_name = $tag['tag_name'];
        }
      }

      
      $orderby_name = '默认排序';
      switch ($orderby) {
        case 'changed':
          //$orderby_name = '最新发布';
          break;
        case 'votecnt':
          $orderby_name = '点赞最多';
          break;
        case 'collectcnt':
          $orderby_name = '收藏最多';
          break;
      }
      $this->v->assign('orderby', $orderby);
      $this->v->assign('orderby_name', $orderby_name);
      
      $page_size = 5;
      $node_list = Node_Model::getNodeList($the_cate_id, $the_type_id, $orderby, $order,$the_tag_id, $page_size);
      $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
      $total_page = $GLOBALS['pager_totalpage_arr'][0];

      foreach($node_list AS &$val){
        $val['collect'] = Node_Model::actionRecord($val['nid'], $uid, 'collect');
        $val['love'] = Node_Model::actionRecord($val['nid'], $uid, 'love');
      }

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('node_list', $node_list)->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);
    }
    $response->send($this->v);
  }
  
  /**
   * 编辑贺卡
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function edit(Request $request, Response $response)
  {
    //更换底部导航
    $this->v->assign('nav_no',  3);

    $this->v->set_tplname('mod_node_edit');
    if ($request->is_hashreq()) {
      //node id
      $the_node_id = $request->arg(1);
      if (!is_numeric($the_node_id)) {
        $the_node_id = 0;
      }
      $the_node_id = intval($the_node_id);
      
      $node_info = Node_Model::getNodeInfo($the_node_id);

      $f = $request->get('f','');
      if($f ==''){
        if($node_info['type_id']=='card'){
          $node_info['content_style'] = json_decode($node_info['content_style'], true);
          $node_info['img_style'] = json_decode($node_info['img_style'], true);
          $node_info['frame_style'] = json_decode($node_info['frame_style'], true);
          $node_info['to_style'] = json_decode($node_info['to_style'], true);
          $node_info['from_style'] = json_decode($node_info['from_style'], true);
        }
        $this->v->assign('node_info', $node_info);
        $this->v->assign('the_type_id', $node_info['type_id']);
        $this->v->assign('referURI', '/cate/'.$node_info['cate_id'].',t='.$node_info['type_id']);
      }elseif($f=='user'){
        $nuid = $request->get('nuid',0);
        $user = Member::getUser();
        $node_user = Node_Model::getShareHistoryByNuid($nuid, $user['uid']);
        if($node_info['type_id']=='word'){
          $node_info['content'] = $node_user['content'];
        }elseif($node_info['type_id']=='card'){
          $node_info['content_style'] = json_decode($node_info['content_style'], true);
          $node_info['img_style'] = json_decode($node_info['img_style'], true);
          $node_info['frame_style'] = json_decode($node_info['frame_style'], true);
          $node_info['to_style'] = json_decode($node_info['to_style'], true);
          $node_info['from_style'] = json_decode($node_info['from_style'], true);
          
          $node_info['content'] = $node_user['content'];
          $node_info['img_url'] = $node_user['img'];
          $node_info['to'] = $node_user['card_to'];
          $node_info['from'] = $node_user['card_from'];
        }

        $this->v->assign('node_info', $node_info);
        $this->v->assign('the_type_id', $node_info['type_id']);
        $this->v->assign('referURI', '/user/notice');
      }

    }
    $response->send($this->v);
  }
  /**
   * [save description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function save(Request $request, Response $response)
  {
    $data = ['flag'=>'FAIL', 'data'=>'','msg'=>''];
    if(!Member::isLogined()){
      $data['msg'] = '请先登录';
      $response->sendJSON($data);
    }
    $user = Member::getUser();
    $uid = $user['uid'];

    $nid = $request->post('nid',0);
    $nodeInfo = Node_Model::getNodeInfo($nid);
    $card_img = $request->post('img','');

    if($nodeInfo['type_id']=='card'&&$nodeInfo['has_img']){
      if($card_img==''||preg_match('/http:\/\//',$card_img)){
        $card_img = Config::get('env.site.mobile').$nodeInfo['img_url'];
      }else{
        $file = $request->post('img',''); 
        $b = explode(',', $file);

        $file_data = $b[1];
        $file_data = base64_decode($file_data);
        $img_info = getimagesizefromstring($file_data);
        if($img_info===FALSE){
          $data['data'] = '图片错误';
          $response->sendJSON($data);
        }
        
        $filetype = 'card';
        $extpart = '.jpg';
        //~ create directory
        $targetfilecode = date('d_His').'_'.randchar();
        $targetfile = $targetfilecode.$extpart;
        $targetdir  = './a/'."{$filetype}/".date('Ym').'/';
        if(!is_dir($targetdir)) {
          mkdirs($targetdir, 0777, FALSE);
        }

        $filepath = $targetdir . $targetfile;
        file_put_contents($filepath, $file_data);
        chmod($filepath, 0444);

        $card_img = Config::get('env.site.mobile').$filepath;
      }
    }

    $card_to = $request->post('card_to', '');
    $card_from = $request->post('card_from', '');
    $card_content = $request->post('content','');

    $card = ['uid'=>$uid, 'nid'=>$nid, 'card_to'=>$card_to, 'card_from'=>$card_from, 'content'=>$card_content, 'img'=>$card_img, 'timeline'=>time()];
    switch ($nodeInfo['type_id']) {
      case 'word':
        
        break;
      case 'card':
        $card['cover'] = $nodeInfo['cover_url'];
        break;
      case 'music':
        $card['cover'] = $nodeInfo['icon_url'];
        $card['status'] = 'R';
        break;
      case 'gift':
        $card['cover'] = $nodeInfo['goods_url'];
        $card['status'] = 'R';
        break;    
      default:
        # code...
        break;
    }

    $nuid = Node_Model::saveCard($card);
    if($nuid>0){
      $data['flag'] = 'SUC';
      $data['data'] = ['nuid'=>$nuid,'img'=>$card_img ? $card_img : Config::get('env.site.mobile').$card['cover']];
    }else{
      $data['msg'] = '系统繁忙，请稍后再试';
    }
    $response->sendJSON($data);
    

  }
  /**
   * [action description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function updateShare(Request $request, Response $response){
    $data = ['flag'=>'FAIL', 'data'=>''];
    if(!Member::isLogined()){
      $data['msg'] = '请先登录';
      $response->sendJSON($data);
    }
    $nuid = $request->post('nuid', 0);
    $user = Member::getUser();
    $nid = $request->post('nid', 0);
    if(Node_Model::updateShare($nuid, $user['uid'], $nid)){
      $data['flag'] = 'SUC';
    }
    $response->sendJSON($data);
  }

  /**
   * [love description]
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function action(Request $request, Response $response){
    $data = ['flag'=>'FAIL', 'data'=>array(), 'msg'=>''];
    if(!Member::isLogined()){
      $data['msg'] = '请先登录';
      $response->sendJSON($data);
    }
    $user = Member::getUser();
    $uid = $user['uid'];
    $nid = (int)$request->post('nid');
    $action = $request->arg(2);
    $uid = $_SESSION['uid'];
    if(!in_array($action, array('love', 'collect'))){
      $data['msg'] = '未知操作';
      $response->sendJSON($data);
      exit();  
    }

    $acted = 0;
    if(Node_Model::actionRecord($nid, $uid, $action)){
      if($action=='love'){
       $affected_rows = Node_Model::cancleVote($nid, $uid);
      }elseif($action=='collect'){
        $affected_rows = Node_Model::cancleCollect($nid, $uid);
      }
    }else{
      if($action=='love'){
        $affected_rows = Node_Model::Vote($nid, $uid);
      }elseif($action=='collect'){
        $affected_rows = Node_Model::Collect($nid, $uid);
      }
      $acted = 1;
    }

    if($affected_rows>0){
      $node_info = Node_Model::getNodeInfo($nid);
      $cnt = 0;
      if($action=='love'){
        $cnt = $node_info['votecnt'];
      }elseif($action=='collect'){
        $cnt = $node_info['collectcnt'];
      }
      $data['data'] = ['cnt'=> $cnt, 'acted'=>$acted];
      $data['flag'] = 'SUC';
    }else{
      $data['msg'] = '系统繁忙，请稍后再试';
    }
    $response->sendJSON($data);
  }


 /**
   * 自定义分享
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function share(Request $request, Response $response)
  {
    //更换底部导航
    $this->v->assign('nav_no',  3);

    $this->v->set_tplname('mod_node_edit');
    if ($request->is_hashreq()) {
      $order_no = $request->arg(2);
      import('mall/Mall_Model');
      $order = Mall_Model::getOrderByNo($order_no);
      $this_node_id = 0;
      if(!empty($order)){
        $the_node_id = $order['goods_id'];
      }else{
        exit('订单不存在');
      }
      
      $node_info = Node_Model::getNodeInfo($the_node_id);
      if(!empty($node_info)){
        $node_info['desc'] = preg_replace('/<img.+?\/?>/i', '' , $node_info['desc']);
      }else{
        exit();
      }

      $this->v->assign('node_info', $node_info);
      $this->v->assign('the_type_id', $node_info['type_id']);
    }

    $response->send($this->v);
  }

  public function show(Request $request, Response $response)
  { 
    $this->v = new PageView('','_page_show');
    $ntype = $request->arg(2);
    $id    = $request->arg(3);
    if(empty($id) || !in_array($ntype,['word','card','music','gift'])){
      exit;
    }
    $this->v->assign('ntype', $ntype);
    
    $node_id = $id;
    $user_node_info = [];
    if ($ntype=='card' || $ntype=='word') {
      $user_node_info = Node_Model::getUserCardById($id);
      if(empty($user_node_info)){
        exit;
      }
      $node_id = $user_node_info['nid'];      
    }
    
    $node_info = Node_Model::getNodeInfo($node_id);
    if($node_info['type_id']=='card'){
      $node_info['content_style'] = json_decode($node_info['content_style'], true);
      $node_info['img_style'] = json_decode($node_info['img_style'], true);
      $node_info['frame_style'] = json_decode($node_info['frame_style'], true);
      $node_info['to_style'] = json_decode($node_info['to_style'], true);
      $node_info['from_style'] = json_decode($node_info['from_style'], true);

      $node_info['img_url'] = $user_node_info['img'];
      $node_info['to'] = $user_node_info['card_to'];
      $node_info['from'] = $user_node_info['card_from'];
    }    
    if(empty($node_info)||$node_info['type_id']=='card'||$node_info['type_id']=='word'){
      $node_info['content'] = $user_node_info['content'];
    }
    
    $baseurl = Config::get('env.site.mobile');
    $this->v->assign('node_info', $node_info)->assign('baseurl',$baseurl);
    
    $theuid = empty($_SESSION['uid']) ? 0 : $_SESSION['uid'];
    $this->v->assign('theuid', $theuid);
    $this->v->assign('isSubscribe', Member::isSubscribe($theuid));

    $response->send($this->v);
  }
  /**
   * 推荐
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function recommend(Request $request, Response $response){
    $this->v->set_tplname('mod_node_recommend');
    if ($request->is_hashreq()) {
      //获取推荐10条记录
      $user = Member::getUser();
      $uid = $user['uid'];
      $page_size = 5;
      $recordes = Node_Model::getRecommendNode($page_size);
      $nodes = [];
      foreach($recordes as $v){
        $record = Node_Model::getNodeInfo($v['nid']);
        $record['collect'] = Node_Model::actionRecord($v['nid'], $uid, 'collect');
        $record['love'] = Node_Model::actionRecord($v['nid'], $uid, 'love');
        
        $nodes[] = $record;
      }

      $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
      $total_page = $GLOBALS['pager_totalpage_arr'][0];

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);

      $this->v->assign('nodes', $nodes)->assign('nav_second',0);
      $this->v->assign('nav', 'recommend');
      $this->v->assign('nodes', $nodes);
    }

    $response->send($this->v);
  }
  /**
   * 检索
   * @param  Request  $request  [description]
   * @param  Response $response [description]
   * @return [type]             [description]
   */
  public function search(Request $request, Response $response){
    $this->v->set_tplname('mod_node_search');
    if ($request->is_hashreq()) {
      //获取10条记录
      $keywords = $request->get('keywords','');

      $user = Member::getUser();
      $uid = $user['uid'];
      $page_size = 5;

      if($keywords!=''){
        $recordes = Node_Model::searchNode($keywords,$page_size);
        if(empty($recordes)){
          $recordes = Node_Model::searchNode($keywords,$page_size, 1);
        }

        $nodes = [];
        foreach($recordes as $v){
          $record = Node_Model::getNodeInfo($v['nid']);
          $record['collect'] = Node_Model::actionRecord($v['nid'], $uid, 'collect');
          $record['love'] = Node_Model::actionRecord($v['nid'], $uid, 'love');
          
          $nodes[] = $record;
        }
        $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
        $total_page = $GLOBALS['pager_totalpage_arr'][0];
        
      }else{
        $nodes = [];
        $next_page = 0;
        $total_page = -1;
      }

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);

      $this->v->assign('nodes', $nodes)->assign('nav_second',0);
      $this->v->assign('nav', 'recommend');
      $this->v->assign('keywords', $keywords);
    }

    $response->send($this->v);
  }
}
 
/*----- END FILE: Node_Controller.php -----*/