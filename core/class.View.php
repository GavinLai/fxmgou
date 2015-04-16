<?php
/**
 * SimPHP View Base Class
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class View extends CBase {
  
  /**
   * Template Object
   * @var Template
   */
  protected $tpl = null;
  
  /**
   * Template Driver Class Name
   * @var string
   */
  protected $tpl_driver_class = '';
  
  /**
   * Template File Postfix
   * @var string
   */
  protected $tpl_postfix = '';
  
  /**
   * Name of Template for rendering
   * @var string
   */
  protected $tpl_name = '';
  
  /**
   * Constructor
   * 
   * @param string $tpl_name, Name of Template for rendering
   */
  public function __construct($tpl_name) {
    
    $this->tpl_driver_class = Config::get('env.tplclass','Smarty');
    $this->tpl_postfix = Config::get('env.tplpostfix','.htm');
    $this->tpl_name = $this->tpl_realpath($tpl_name);
    $this->modroot = SimPHP::$gConfig['modroot'];
    
    $tpl_config = array('caching'       => Config::get('env.tplcache',0),
                        'cache_lifetime'=> Config::get('env.tplcache_expires',300),
                        'compile_check' => Config::get('env.tplcompile_check',1),
                        'force_compile' => Config::get('env.tplforce_compile',0),
                        'debugging'     => Config::get('env.tpldebug',0));
    
    $tpldir = Config::get('env.sitetheme','default');
    if ($this->modroot != 'modules') {
      $tpldir = $this->modroot;
    }
    $template_dir = SIMPHP_ROOT . "/themes/{$tpldir}";
    $compiled_dir = SIMPHP_ROOT . Config::get('env.tplcachedir') . "/{$tpldir}/compiled";
    $cached_dir   = SIMPHP_ROOT . Config::get('env.tplcachedir') . "/{$tpldir}/cached";
    $tpl_config['template_dir'] = $template_dir;
    $tpl_config['compile_dir']  = $compiled_dir;
    $tpl_config['cache_dir']    = $cached_dir;
    
    try {
      if (!is_dir($compiled_dir) && !mkdirs($compiled_dir) && !is_writable($compiled_dir)) {
        throw new DirWritableException($compiled_dir);
      }
      if (Config::get('env.tplcache') && !is_dir($cached_dir) && !mkdirs($cached_dir) && !is_writable($cached_dir)) {
        throw new DirWritableException($cached_dir);
      }
    }
    catch (DirWritableException $e) {
      trigger_error($e->getMessage(), E_USER_ERROR);
    }
    
    $this->tpl = Template::I(array('driverClass'  => $this->tpl_driver_class,
                                   'driverConfig' => $tpl_config,
                            ));
    $this->assign('contextpath', Config::get('env.contextpath','/'));
    $this->assign_by_ref('user', $GLOBALS['user']);
  }
  
  /**
   * Set $tpl_name
   * @param string $tpl_name
   * @return View
   */
  public function set_tplname($tpl_name) {
    $this->tpl_name = $this->tpl_realpath($tpl_name);
    return $this;
  }
  
  /**
   * Get template real file path
   * @param string $tpl_name
   * @param bool $is_abs_path when true, then return the true template file absolute path
   */
  public static function tpl_realpath($tpl_name, $is_abs_path = false) {
    if (preg_match("/^mod_([a-z]+)_/", $tpl_name, $matchs)) {	// module template
      $tpl_name = ($is_abs_path ? '' : 'file:').SIMPHP_ROOT."/".SimPHP::$gConfig['modroot']."/{$matchs[1]}/tpl/{$tpl_name}";
    }
    elseif ($is_abs_path) {
      $tpldir = Config::get('env.sitetheme','default');
      if (SimPHP::$gConfig['modroot'] != 'modules') {
        $tpldir = SimPHP::$gConfig['modroot'];
      }
      $tpl_name = SIMPHP_ROOT . "/themes/{$tpldir}/{$tpl_name}";
    }
    $tplpostfix = Config::get('env.tplpostfix','.htm');
    return $tpl_name . (false===strrpos($tpl_name, $tplpostfix) ? $tplpostfix : '');
  }
  
  /**
   * Assign var
   * @param string $tpl_var
   * @param mixed $value
   * @return View
   */
  public function assign($tpl_var, $value) {
    $this->tpl->assign($tpl_var, $value);
    return $this;
  }
  
  /**
   * Assign var by reference
   * @param string $tpl_var
   * @param mixed $value
   * @return View
   */
  public function assign_by_ref($tpl_var, &$value) {
    $this->tpl->assign_by_ref($tpl_var, $value);
    return $this;
  }
  
  /**
   * Render a template
   * 
   */
  public function render($tpl_name = null) {
    return $this->filter_output($this->tpl->render(isset($tpl_name)
                                                   ? $tpl_name
                                                   : $this->tpl_name,
                                                   null,
                                                   null,
                                                   false));
  }
  
  /**
   * Filter render output
   * @param string $content
   * @return string
   */
  public function filter_output($content) {
    return $content;
  }
  
  /**
   * Magically converts view object to string.
   *
   * @return string
   */
  public function __toString() {
    try {
      return $this->render();
    }
    catch (Exception $e) {
      trigger_error($e->getMessage(), E_USER_WARNING);
    }
  }
  
  /**
   * Set list order parameters
   *
   * @param String $default_field
   *   default sort field
   * @param String $default_order
   *   default sort field
   * @return Array
   *   containing 'three' elements: 
   *   array(
   *     '0' => 'orderby',
   *     '1' => 'order',
   *     '2' => 'order part of url',
   *     'orderby' => 'orderby',
   *     'order'   => 'order',
   *     'orderurl'=> 'order part of url',
   *   )
   */
  public function set_listorder($default_field = 'rid', $default_order = 'DESC') {
    $orderby = isset($_GET['orderby']) && ''!=$_GET['orderby'] ? $_GET['orderby'] : $default_field;  
    $order   = isset($_GET['order']) ? strtoupper($_GET['order']) : strtoupper($default_order);
    if (!in_array($order, array('DESC','ASC'))) {
      $order = 'DESC';
    }
    
    $orderurl = 'orderby='.$orderby.'&order='.strtolower($order);
    $this->assign('listorderby', $orderby);
    $this->assign('listorder', $order);
  
    return array(0=>$orderby, 1=>$order, 2=>$orderurl,'orderby'=>$orderby,'order'=>$order,'orderurl'=>$orderurl);
  }
  
  /**
   * Generate SimPHP page links
   * 
   * @param string $q
   */
  public static function link($q='') {
    $cleanurl = Config::get('env.cleanurl',0);
    $url = Config::get('env.contextpath','/');
    
    if ($cleanurl) {
      $url .= $q;
    }
    else {
      $url .= '?q='.$q;
    }
    
    return $url;
  }
  
  /**
   * Url link connector
   * @return string
   */
  public static function link_connector() {
    return Config::get('env.cleanurl',0) ? '?' : '&';
  }
  
}
 
/*----- END FILE: class.View.php -----*/