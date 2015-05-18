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
      $goods_latest = Default_Model::getGoodsList('latest',0,6);
      $this->v->assign('goods_latest',$goods_latest);
      
      //获取一级显示分类
      $category_top = Default_Model::getCategory(0, FALSE);
      foreach ($category_top AS &$top) {
        $child_ids = Default_Model::getChildCategoryIds($top['cat_id']);
        $cat_ids   = array_merge([$top['cat_id']], $child_ids);
        $goods_cate = Default_Model::getGoodsList('category',0,6,['cat_ids'=>$cat_ids]);
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
    
    if ($request->is_hashreq()) {
      
      //获取最新上架
      $goods_latest = Default_Model::getGoodsList('latest',0,50);
      $this->v->assign('goods_latest',$goods_latest);
      
    }
    else {
  
    }
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
        $errmsg = '查询商品不存在: goods_id: '.$goods_id;
      }
      else {
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
    
    }
    $response->send($this->v);
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