<?php
/**
 * 默认(一般首页)模块控制器，此控制器必须
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Default_Controller extends Controller {

  private $_nav_no     = 1;
  private $_nav        = 'home';
  private $_nav_second = '';
  
  /**
   * hook init
   * 
   * @param string $action
   * @param Request $request
   * @param Response $response
   */
  public function init($action, Request $request, Response $response) {
    $this->v = new PageView();
    $this->v->assign('nav_no',     $this->_nav_no)
            ->assign('nav',        $this->_nav)
            ->assign('nav_second', $this->_nav_second);
  }
  
  /**
   * hook menu
   * @see Controller::menu()
   */
  public function menu() {
    return [
      'default/cate/%d' => 'index',
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
      
      import('node/Node_Model');
      $feedlist = Node_Model::getFeedList(['cate_id'=>$cate_id],$page, $limit, $hasmore);
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
   * action 'guide'
   *
   * @param Request $request
   * @param Response $response
   */
  public function guide(Request $request, Response $response) {
    $this->v = new PageView('mod_default_guide', '_page_public');
    
    //$ua = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0) ';
    //$ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Mobile/11D257 MicroMessenger/5.4 NetType/WIFI';
    $ieVer= Request::ieVer();
    $testval = "is IE: ".($ieVer ? 'YES' : 'NO').", Ver: {$ieVer}";
    $this->v->assign('testval', $testval);
    
    $response->send($this->v);
  }

}
 
/*----- END FILE: Default_Controller.php -----*/