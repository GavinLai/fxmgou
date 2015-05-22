<?php
/**
 * 默认Model 
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Default_Model extends Model {
  
  public static function getAd($ad_name){
  	$sql = "SELECT * FROM {ad} WHERE ad_name='%s' ";
  	$ad = D()->get_one($sql, $ad_name);
  	if(empty($ad)){
  		return [];
  	}else{
  		$sql = "SELECT * FROM {ad_pic} WHERE ad_id = %d ORDER BY `sort` DESC LIMIT %d ";
  		$ad_list = D()->query($sql, $ad['ad_id'], $ad['max_num'])->fetch_array_all();
  		return $ad_list;
  	}
  }

  public static function getCategory($parent_id = 0, $just_id = FALSE) {
    $ectb  = ectable('category');
    $sql   = "SELECT `cat_id`,`cat_name` FROM {$ectb} WHERE `is_show`=1 AND `parent_id`=%d ORDER BY `sort_order` ASC";
    $ret   = D()->raw_query($sql, $parent_id)->fetch_array_all();
    if ($just_id && !empty($ret)) {
      foreach ($ret AS &$it) {
        $it = $it['cat_id'];
      }
    }
    return $ret;
  }
  
  public static function getChildCategoryIds($parent_id = 0, Array &$output = array()) {
    $child_ids_cur = self::getCategory($parent_id, TRUE);
    if (!empty($child_ids_cur)) {
      $output = array_merge($output,$child_ids_cur);
      foreach ($child_ids_cur AS $child_id) {
        self::getChildCategoryIds($child_id, $output);
      }
    }
    return $output;
  }
  
  public static function getGoodsList($type, $start = 0, $limit = 10, Array $extra = array()) {
    
    $ectb = ectable('goods');
    $ectb_rel = ectable('order_goods');
    
    $zonghe_order = '';
    if (''==$type||'zonghe'==$type) { //综合排序，算法：zonghe_order = (click_count * 1 + collect_count * 100 + paid_order_count * 1000)
      $zonghe_order = ",(click_count * 1 + collect_count * 100 + paid_order_count * 1000) AS zonghe_order";
    }
    
    $fields = "`goods_id`,`cat_id`,`goods_sn`,`goods_name`,`click_count`,`collect_count`,`paid_order_count`,`brand_id`,`goods_number`,`market_price`,`shop_price`,`goods_thumb`,`goods_img`,`add_time`,`last_update`{$zonghe_order}";
    $sqlpre = "SELECT {$fields} FROM {$ectb} WHERE `is_on_sale`=1 AND `goods_img`<>''";
    $ret    = [];
    
    $sql    = '';
    if (''==$type||'zonghe'==$type) { //综合排序
      $sql  = $sqlpre . "ORDER BY `zonghe_order` DESC";
    }
    elseif ('click'==$type) { //按点击数
      $sql  = $sqlpre . "ORDER BY `click_count` DESC";
    }
    elseif ('collect'==$type) { //按收藏数
      $sql  = $sqlpre . "ORDER BY `collect_count` DESC";
    }
    elseif ('paid'==$type) { //按订单数
      $sql  = $sqlpre . "ORDER BY `paid_order_count` DESC";
    }
    elseif ('price_low2top'==$type) { //价格从低到高
      $sql  = $sqlpre . "ORDER BY `shop_price` ASC";
    }
    elseif ('price_top2low'==$type) { //价格从高到低
      $sql  = $sqlpre . "ORDER BY `shop_price` DESC";
    }
    elseif ('latest'==$type) { //新品
      $sql  = $sqlpre . "ORDER BY `add_time` DESC";
    }
    elseif ('category'==$type) { //按分类
      $cat_id_in = '';
      if (isset($extra['cat_ids']) && !empty($extra['cat_ids'])) {
        $cat_id_in = implode(',', $extra['cat_ids']);
        $sql  = $sqlpre . "AND `cat_id` IN({$cat_id_in}) ORDER BY `add_time` DESC";
      }
    }
    if (''!=$sql) {
      $sql.= " LIMIT %d, %d";
      $ret = D()->raw_query($sql,$start,$limit)->fetch_array_all();
    }
    
    if (!empty($ret)) {
      $purl = C('env.site.shop').'/';
      foreach ($ret AS &$it) {
        $it['goods_thumb'] = $purl . $it['goods_thumb'];
        $it['goods_img']   = $purl . $it['goods_img'];
      }
    }
    
    return $ret;
  }
  
  public static function getGoodsInfo($goods_id) {
    if (empty($goods_id) || !is_numeric($goods_id)) {
      return FALSE;
    }
    $ectb  = ectable('goods');
    $sql   = "SELECT * FROM {$ectb} WHERE `goods_id`=%d AND `is_on_sale`=1 AND `goods_img`<>''";
    $ret   = D()->raw_query($sql,$goods_id)->get_one();
    return $ret;
  }
  
}
 
/*----- END FILE: Default_Model.php -----*/