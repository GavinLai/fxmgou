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
  
  public static function getCategoryName($cat_id) {
    $cat_id = intval($cat_id);
    if (empty($cat_id)) return false;
    $cat_name = D()->from(ectable('category'))->where(['cat_id'=>$cat_id])->select('cat_name')->result();
    return $cat_name;
  }

  public static function getCategory($parent_id = 0, $just_id = FALSE) {
    $ectb  = ectable('category');
    $sql   = "SELECT `cat_id`,`cat_name`,`is_show` FROM {$ectb} WHERE `is_show`=1 AND `parent_id`=%d ORDER BY `sort_order` ASC";
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
  
  public static function getGoodsList($type = '', $order = '', $start = 0, $limit = 10, Array $extra = array()) {
    
    $ectb = ectable('goods');
    $ectb_coll = ectable('collect_goods');
    
    $zonghe_order = '';
    if (''==$order||'zonghe'==$order) { //综合排序，算法：zonghe_order = (click_count * 1 + collect_count * 100 + paid_order_count * 1000)
      $zonghe_order = ",(g.click_count * 1 + g.collect_count * 100 + g.paid_order_count * 1000) AS zonghe_order";
    }
    
    $user_id= $GLOBALS['user']->ec_user_id;
    $user_id= intval($user_id);
    $fields = "g.`goods_id`,g.`cat_id`,g.`goods_sn`,g.`goods_name`,g.`click_count`,g.`collect_count`,g.`paid_order_count`,g.`brand_id`,g.`goods_number`,g.`market_price`,g.`shop_price`,g.`goods_thumb`,g.`goods_img`,g.`add_time`,g.`last_update`{$zonghe_order}";
    $fields.= ",IF(cg.goods_id is NULL, 0, 1) AS collected";
    $sqlpre = "SELECT {$fields} FROM {$ectb} g LEFT JOIN {$ectb_coll} cg ON cg.user_id={$user_id} AND g.goods_id=cg.goods_id WHERE g.`is_on_sale`=1 AND g.`goods_img`<>''";
    $ret    = [];
    
    $sql    = '';
    if ('new_arrival'==$type) { //新品
      $sql  = $sqlpre . " ORDER BY g.`add_time` DESC";
    }
    else { //按条件查询
      
      // 条件查询
      $cat_id_in = '';
      if (isset($extra['cat_ids']) && !empty($extra['cat_ids'])) {
        if (is_array($extra['cat_ids'])) {
          $cat_id_in = implode(',', $extra['cat_ids']);
          $sqlpre .= " AND g.`cat_id` IN({$cat_id_in})";
        }
        else {
          $sqlpre .= " AND g.`cat_id`=".$extra['cat_ids'];
        }
      }
      if (isset($extra['brand_id']) && !empty($extra['brand_id'])) {
        $sqlpre .= " AND g.`brand_id`=".$extra['brand_id'];
      }
      if (isset($extra['price_from']) && !empty($extra['price_from'])) {
        $sqlpre .= " AND g.`shop_price`>=".$extra['price_from'];
      }
      if (isset($extra['price_to']) && !empty($extra['price_to'])) {
        $sqlpre .= " AND g.`shop_price`<=".$extra['price_to'];
      }
      
      // 排序
      if (''==$order||'zonghe'==$order) { //综合排序
        $sql  = $sqlpre . " ORDER BY `zonghe_order` DESC";
      }
      elseif ('click'==$order) { //按点击数
        $sql  = $sqlpre . " ORDER BY g.`click_count` DESC";
      }
      elseif ('collect'==$order) { //按收藏数
        $sql  = $sqlpre . " ORDER BY g.`collect_count` DESC";
      }
      elseif ('paid'==$order) { //按订单数
        $sql  = $sqlpre . " ORDER BY g.`paid_order_count` DESC";
      }
      elseif ('price_low2top'==$order) { //价格从低到高
        $sql  = $sqlpre . " ORDER BY g.`shop_price` ASC";
      }
      elseif ('price_top2low'==$order) { //价格从高到低
        $sql  = $sqlpre . " ORDER BY g.`shop_price` DESC";
      }
      else { //默认按添加时间倒排
        $sql  = $sqlpre . " ORDER BY g.`add_time` DESC";
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