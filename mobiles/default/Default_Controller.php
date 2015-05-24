<?php
/**
 * 默认(一般首页)模块控制器，此控制器必须
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Default_Controller extends Controller {

  private $nav_no     = 1;       //主导航id
  private $topnav_no  = 0;       //顶部导航id
  private $nav_flag1  = 'home';  //导航标识1
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
    $this->v = new PageView();
    $this->v->add_render_filter(function(View $v){
      $v->assign('nav_no',  $this->nav_no)
        ->assign('topnav_no',  $this->topnav_no)
        ->assign('nav_flag1',  $this->nav_flag1)
        ->assign('nav_flag2',  $this->nav_flag2)
        ->assign('nav_flag3',  $this->nav_flag3);
    });
  }
  
  /**
   * hook menu
   * @see Controller::menu()
   */
  public function menu()
  {
    return [
      
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
    $this->v->set_tplname('mod_default_index');
    if ($request->is_hashreq()) {
      $page    = $request->get('p',1);
      $cate_id = $request->arg(2);
      $limit= 10;
      $hasmore   = false;
      $page      = intval($page);
      $cate_id   = $cate_id ? intval($cate_id) : 0;
      $pageronly = $page > 1 ? TRUE : FALSE;


      //获取广告位
      $ad_name = 'default_index';
      $ad = Default_Model::getAd($ad_name);
      
      //获取最新上架
      $goods_latest = Default_Model::getGoodsList('latest','',0,6);
      $this->v->assign('goods_latest',$goods_latest);
      
      //获取一级显示分类
      $category_top = Default_Model::getCategory(0, FALSE);
      foreach ($category_top AS &$top) {
        $child_ids = Default_Model::getChildCategoryIds($top['cat_id']);
        $cat_ids   = array_merge([$top['cat_id']], $child_ids);
        $goods_cate = Default_Model::getGoodsList('category','latest',0,6,['cat_ids'=>$cat_ids]);
        $top['goods_set'] = $goods_cate;
      }
      $this->v->assign('category_top',$category_top);
      
      $feedlist = [];
      $this->v->assign('feedlist', $feedlist)
              ->assign('top_cate_id', $cate_id)
              ->assign('nextpage', $page+1)
              ->assign('limit', $limit)
              ->assign('pageronly', $pageronly)
              ->assign('hasmore', $hasmore)
              ->assign('ad', $ad);
      
    }
    else{
      
    }
    $response->send($this->v);
  }
  
  /**
   * default action 'explore'
   *
   * @param Request $request
   * @param Response $response
   */
  public function explore(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_default_explore');
    $this->nav_flag1 = 'explore';
    $this->topnav_no = 1; // >0: 表示有topnav bar，具体值标识哪个topnav bar(有多个的情况下)
    
    // 排序数据
    $order_set = [
      'zonghe'        => ['id' => 'zonghe'       , 'name' => '综合排序'   , 'is_show' => 1, 'is_last' => 0],
      'click'         => ['id' => 'click'        , 'name' => '人气最高'   , 'is_show' => 1, 'is_last' => 0],
      'collect'       => ['id' => 'collect'      , 'name' => '收藏最多'   , 'is_show' => 1, 'is_last' => 0],
      'paid'          => ['id' => 'paid'         , 'name' => '销量最好'   , 'is_show' => 1, 'is_last' => 0],
      'latest'        => ['id' => 'latest'       , 'name' => '最新添加'   , 'is_show' => 0, 'is_last' => 0],
      'price_low2top' => ['id' => 'price_low2top', 'name' => '价格从低到高', 'is_show' => 1, 'is_last' => 0],
      'price_top2low' => ['id' => 'price_top2low', 'name' => '价格从高到低', 'is_show' => 1, 'is_last' => 1],
    ];
    $this->v->assign('order_set', $order_set);
    
    // GET数据
    $order  = $request->get('o', 'zonghe');
    $type   = $request->get('t', 'category');
    $cat_id = $request->get('cid', 0);
    $cat_id = intval($cat_id);
    if (!in_array($order, array_keys($order_set))) {
      $order = 'latest';
    }
    if (!in_array($type, ['latest','category'])) {
      $type = 'category';
    }
    $cat_name = Default_Model::getCategoryName($cat_id);
    if (empty($cat_name)) {
      $cat_name = '分类';
    }
    
    // 顶级分类，用于分类筛选
    $category_top = Default_Model::getCategory(0, FALSE);
    $this->v->assign('filter_categories', $category_top);
    $this->v->assign('filter_category_num', count($category_top));
    
    if ($request->is_hashreq()) {
      
      $goods_list = [];
      if ('latest'==$type) { //新品
        $goods_list = Default_Model::getGoodsList('latest','',0,20);
      }
      else {
        $extra = [];
        if ($cat_id) {
          $child_ids = Default_Model::getChildCategoryIds($cat_id);
          $cat_ids   = array_merge([$cat_id], $child_ids);
          $extra     = ['cat_ids'=>$cat_ids];
        }
        $goods_list = Default_Model::getGoodsList('category',$order,0,50,$extra);
      }
      $this->v->assign('goods_list',$goods_list);
      
    }
    else {
      
    }
    
    $this->v->assign('order', $order)->assign('type', $type);
    $this->v->assign('the_cat_id', $cat_id)->assign('the_cat_name', $cat_name);
    $response->send($this->v);
  }
  
  public function item(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_default_item');
    $this->nav_no   = 2;
    $this->nav_flag1= 'item';
    
    $goods_id = $request->arg(2);
    $this->v->assign('the_goods_id', $goods_id);
    
    if ($request->is_hashreq()) {
    
      $errmsg   = '';
      
      //获取商品信息
      $goods_info = Default_Model::getGoodsInfo($goods_id);
      if (empty($goods_info)) {
        $errmsg = "查询商品不存在或已下架(商品id: {$goods_id})";
      }
      else {
        
        Goods::addGoodsClickCnt($goods_id);
        
        //原产地名称和品牌信息
        $goods_info['origin_place_name'] = '';
        $goods_info['brand_info'] = [];
        
        $cat_info = Goods::getCategoryInfo($goods_info['origin_place_id'], false);
        if (!empty($cat_info)) {
          $goods_info['origin_place_name'] = $cat_info['cat_name'];
        }
        $brand_info = Goods::getBrandInfo($goods_info['brand_id']);
        if (!empty($brand_info)) {
          $goods_info['brand_info'] = $brand_info;
        }
        
        //检查是否已经收藏
        global $user;
        $goods_info['is_collect'] = false;
        if ($user->uid) {
          $goods_info['is_collect'] = Goods::isCollected($goods_id, $user->ec_user_id);
        }
        
        $purl = C('env.site.shop');
        $goods_info['goods_thumb']  = Goods::goods_picurl($goods_info['goods_thumb']);
        $goods_info['goods_img']    = Goods::goods_picurl($goods_info['goods_img']);
        $goods_info['original_img'] = Goods::goods_picurl($goods_info['original_img']);
        
        include (SIMPHP_CORE.'/libs/htmlparser/simple_html_dom.php');
        $dom = str_get_html($goods_info['goods_desc']);
        $imgs= $dom->find('img');
        $imgs_src = [];
        if (!empty($imgs)) {
          foreach ($imgs AS $img) {
            $imgs_src[] = $img->getAttribute('src');
          }
          
          foreach($imgs_src as $psrc) {
            if(preg_match('/^\//', $psrc)) { //表示本地上传图片
              $goods_info['goods_desc'] = str_replace('src="'.$psrc.'"', 'src="'.$purl . $psrc.'"', $goods_info['goods_desc']);
            }
          }
        }
      }
      
      $this->v->assign('errmsg', $errmsg)
              ->assign('goods_info', $goods_info);
      
    
    }
    else {
      $refer = $request->refer();
      $backurl = U('explore');
      if ($refer) {
        $backurl = $refer;
      }
      $this->v->assign('backurl', $backurl);
    }
    $response->send($this->v);
  }
  
  public function item_collect(Request $request, Response $response) {
    
    if ($request->is_post()) {
      
      $ret = ['flag'=>'FAIL','msg'=>'收藏失败'];
      $goods_id = $request->post('goods_id',0);
      
      $ec_user_id = $GLOBALS['user']->ec_user_id;
      if (!$ec_user_id) {
        $ret['msg'] = '未登录，请先登录';
        $response->sendJSON($ret);
      }
      
      $ret = ['flag'=>'SUC','msg'=>'收藏成功'];
      
      $res = Goods::goodsCollecting($goods_id, $ec_user_id);
      if ($res=='collected') {
        $ret['msg'] = '已收藏';
      }
      elseif ($res=='collect_fail') {
        $ret = ['flag'=>'FAIL','msg'=>'收藏失败'];
      }
      
      $response->sendJSON($ret);
      
    }
    
  }

  public function about(Request $request, Response $response)
  {
    $this->v->set_tplname('mod_default_about');
    $this->nav_flag1 = 'about';
    
    if ($request->is_hashreq()) {
      
    }
    else {
      
    }
    $response->send($this->v);
  }
  
}
 
/*----- END FILE: Default_Controller.php -----*/