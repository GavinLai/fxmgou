<?php
/**
 * Page View Class 
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class PageView extends View {
  
  /**
   * Global base page Template
   * @var string
   */
  protected $tpl_base = '';
  
  /**
   * Global header Template
   * @var string
   */
  protected $tpl_header = '';
  
  /**
   * Global footer Template
   * @var string
   */
  protected $tpl_footer = '';
  
  /**
   * Global content Template
   * @var string
   */
  protected $tpl_content = '';
  
  /**
   * Constructor
   * 
   * @param string $tpl_name, Name of Template for rendering
   * @param string $tpl_base, Base Page Template Name, default to '_page'
   * @param array  $tpl_extra, other tpl part, for example: $tpl_extra['header'],$tpl_extra['footer']...and so on 
   */
  public function __construct($tpl_name = '', $tpl_base = '_page', $tpl_extra = array()) {
    
    parent::__construct($tpl_name);
    
    // Make support $tpl_extra => $tpl_base
    if (is_array($tpl_base)) {
      $tpl_extra = $tpl_base;
      $tpl_base  = '_page';
    }
    
    $_header = isset($tpl_extra['header']) ? $tpl_extra['header'] : '_header'; 
    $_footer = isset($tpl_extra['footer']) ? $tpl_extra['footer'] : '_footer'; 
    $this->tpl_base    = $tpl_base . $this->tpl_postfix;
    $this->tpl_header  = $_header . $this->tpl_postfix;
    $this->tpl_footer  = $_footer . $this->tpl_postfix;
    $this->tpl_content = $this->tpl_realpath($tpl_name);
    
    $this->assign('tpl_header', $this->tpl_header)
         ->assign('tpl_footer', $this->tpl_footer)
         ->assign('tpl_content', $this->tpl_content)
         ->assign('seo',Config::get('seo.default'));
  }
  
  /**
   * set $tpl_name
   * @param string $tpl_name
   * @return PageView
   */
  public function set_tplname($tpl_name) {
    $this->tpl_content = $this->tpl_name = $this->tpl_realpath($tpl_name);
    $this->assign('tpl_content', $this->tpl_content);
    return $this;
  }
  
  /**
   * set base template
   * @param string $tpl_base
   * @return PageView
   */
  public function setTplBase($tpl_base = '_page') {
    $this->tpl_base = $tpl_base . $this->tpl_postfix;
    return $this;
  }
  
  /**
   * render a template
   */
  public function render($tpl_name = null) {
    if (Request::is_hashreq()) {
      return parent::render($this->tpl_content);
    }
    return parent::render($this->tpl_base);
  }
  
  /**
   * filter render output
   * @param string $content
   * @return string
   */
  public function filter_output($content) {
    
    $head_css = implode("\n", isset($GLOBALS['_CSSPATHS']['head']) ? $GLOBALS['_CSSPATHS']['head']: array());
    $head_js  = implode("\n", isset($GLOBALS['_JSPATHS']['head'])  ? $GLOBALS['_JSPATHS']['head'] : array());
    $foot_js  = implode("\n", isset($GLOBALS['_JSPATHS']['foot'])  ? $GLOBALS['_JSPATHS']['foot'] : array());
    $foot_css = implode("\n", isset($GLOBALS['_CSSPATHS']['foot']) ? $GLOBALS['_CSSPATHS']['foot']: array());
    
    $content  = str_replace(array('<!--#HEAD_CSS#-->','<!--#HEAD_JS#-->','<!--#FOOT_JS#-->','<!--#FOOT_CSS#-->'), 
                            array($head_css,$head_js,$foot_js,$foot_css), $content);
    
    return $content;
  }
}

 
/*----- END FILE: class.PageView.php -----*/