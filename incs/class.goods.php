<?php
/**
 * 与Goods相关常用方法
 *
 * @author afar<afarliu@163.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Goods {
  
  public static function getCategoryInfo($cat_id = 0, $just_show = TRUE, $just_ret_id = FALSE) {
    $ectb  = ectable('category');
    $show  = $just_show ? 'AND `is_show`=1' : '';
    $sql   = "SELECT `cat_id`,`cat_name`,`parent_id`,`is_show` FROM {$ectb} WHERE `cat_id`=%d {$show}";
    $ret   = D()->get_one($sql, $cat_id);
    if ($just_ret_id && !empty($ret)) {
      return $ret['cat_id'];
    }
    return $ret;
  }
  
  public static function getParentCatesChain($cat_id, Array &$output = []) {
    $currCateInfo = self::getCategoryInfo($cat_id, FALSE, FALSE);
    if (!empty($currCateInfo)) {
      if (0!=$currCateInfo['parent_id']) {
        self::getParentCatesChain($currCateInfo['parent_id'], $output);
      }
      $output[] = $currCateInfo;
    }
    return $output;
  }
  
  public static function getGoodsInfo($goods_id, Array $ctrl = array('is_on_sale'=>1,'goods_img'=>1)) {
    if (empty($goods_id) || !is_numeric($goods_id)) {
      return FALSE;
    }
    $ectb  = ectable('goods');
    $where_ctrl = '';
    if (!empty($ctrl)) {
      if (isset($ctrl['is_on_sale']) && $ctrl['is_on_sale']) {
        $where_ctrl .= " AND `is_on_sale`=1";
      }
      if (isset($ctrl['goods_img']) && $ctrl['goods_img']) {
        $where_ctrl .= " AND `goods_img`<>''";
      }
    }
    $sql   = "SELECT * FROM {$ectb} WHERE `goods_id`=%d {$where_ctrl}";
    $ret   = D()->raw_query($sql,$goods_id)->get_one();
    return $ret;
  }
  
}
 
/*----- END FILE: class.goods.php -----*/