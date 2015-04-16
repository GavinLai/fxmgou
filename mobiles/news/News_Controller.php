<?php
/**
 * Node Controller
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class News_Controller extends Controller {
  
  private $_nav_no     = 2;
  private $_nav        = '';
  private $_nav_second = '';
  
  public function menu() {
    return [
      'news/cate/%d' => 'cate',
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

  public function index(Request $request, Response $response){
    //更换底部导航
    $this->v->assign('nav_no',  0);

    $this->v->set_tplname('mod_news_index');
    if ($request->is_hashreq()) {
      $page_size = 5;
      $list = News_Model::getList('created', 'DESC', $page_size);

      $next_page = $GLOBALS['pager_currpage_arr'][0]+1;
      $total_page = $GLOBALS['pager_totalpage_arr'][0];

      $show_blk = ['blk_1'];//显示区域
      if(isset($_GET['p'])){
        $show_blk = [];
      }
      $this->v->assign('show_blk',$show_blk);
      $this->v->assign('total_page',$total_page)->assign('next_page', $next_page);

      $this->v->assign('list', $list);
    }

    $response->send($this->v);
  }
  
  
  public function detail(Request $request, Response $response){
    //更换底部导航
    $this->v->assign('nav_no',  0);

    $this->v->set_tplname('mod_news_detail');
    if ($request->is_hashreq()) {
      $nid = $request->arg(2);
      $info = News_Model::getNewsBynid($nid);
      $this->v->assign('info', $info);
    }

    $response->send($this->v);
  }
  
  


}