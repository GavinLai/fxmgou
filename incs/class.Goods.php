<?php
/**
 * 与Goods相关常用方法
 *
 * @author afar<afarliu@163.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Goods {
  
  public static function goods_url($goods_id, $with_prefix = FALSE) {
    static $urlpre;
    if ($with_prefix && !isset($urlpre)) $urlpre = C('env.site.mobile');
    return ($with_prefix ? $urlpre.'/item/' : '/item/').$goods_id;
  }
  
  public static function goods_picurl($goods_pic) {
    static $urlpre;
    if (!isset($urlpre)) $urlpre = C('env.site.shop').'/';
    return $urlpre.$goods_pic;
  }
  
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
  
  public static function getGoodsGallery($goods_id) {
    if (empty($goods_id) || !is_numeric($goods_id)) {
      return FALSE;
    }
    $ectb = ectable('goods_gallery');
    $sql  = "SELECT * FROM {$ectb} WHERE `goods_id`=%d ORDER BY `img_id` ASC";
    $ret  = D()->raw_query($sql,$goods_id)->fetch_array_all();
    return $ret;
  }
  
  public static function getUserCartNum($userid_or_sessid = NULL, $target_goods_id = NULL) {
    if (is_null($userid_or_sessid)) $userid_or_sessid = $GLOBALS['user']->ec_user_id;
    $ectb = ectable('cart');
    $where= self::getCartOwnerSql($userid_or_sessid);
    if ($target_goods_id) {
      $where .= " AND `goods_id`=%d";
    }
    $sql  = "SELECT SUM(`goods_number`) AS num FROM {$ectb} WHERE {$where}";
    $ret  = D()->raw_query($sql,$userid_or_sessid,$target_goods_id)->result();
    return $ret;
  }
  
  public static function getUserCart($userid_or_sessid = NULL) {
    if (is_null($userid_or_sessid)) $userid_or_sessid = $GLOBALS['user']->ec_user_id;
    $ectb = ectable('cart');
    $where= self::getCartOwnerSql($userid_or_sessid);
    $sql  = "SELECT * FROM {$ectb} WHERE {$where} ORDER BY `rec_id` DESC";
    $ret  = D()->raw_query($sql,$userid_or_sessid)->fetch_array_all();
    if (!empty($ret)) {
      foreach ($ret AS &$g) {
        $g['goods_url']   = self::goods_url($g['goods_id']);
        $g['goods_thumb'] = self::goods_picurl($g['goods_thumb']);
        $g['goods_img']   = self::goods_picurl($g['goods_img']);
      }
    }
    return $ret;
  }
  
  public static function getCartOwnerSql($userid_or_sessid) {
    $where= '';
    if (strlen($userid_or_sessid) > 10) { //$userid_or_sessid is session id
      $where = "`session_id`='%s'";
    }
    else { //$userid_or_sessid is user_id
      $where = "`user_id`=%d";
    }
    return $where;
  }
  
  public static function checkCartGoodsExist($userid_or_sessid, $goods_id) {
    $ectb = ectable('cart');
    $where= self::getCartOwnerSql($userid_or_sessid);
    $sql  = "SELECT `rec_id` FROM {$ectb} WHERE {$where} AND `goods_id`=%d";
    $rec_id = D()->raw_query($sql,$userid_or_sessid, $goods_id)->result();
    return $rec_id;
  }
  
  /**
   * 改变购物车中商品的购买数量
   * 
   * @param integer $userid_or_sessid
   * @param integer $goods_id
   * @param integer $inc
   * @param boolean $is_cart_rec_id , when $is_cart_rec_id is true,  $goods_id indicating the cart record id
   * @param boolean $is_fixed_value , when $is_fixed_value is true, $inc is a fixed value, not an increment
   */
  public static function changeCartGoodsNum($userid_or_sessid, $goods_id, $inc = 1, $is_cart_rec_id = false, $is_fixed_value = false) {
    $ectb = ectable('cart');
    if ($is_cart_rec_id) {
      $where = "`rec_id`=%d";
    }
    else {
      $where = "`goods_id`=%d AND ".self::getCartOwnerSql($userid_or_sessid);
    }
    
    $inc = intval($inc);
    $setpart = "`goods_number`=`goods_number`+{$inc}";
    if ($is_fixed_value) {
      $setpart = "`goods_number`={$inc}";
    }
    $sql  = "UPDATE {$ectb} SET {$setpart} WHERE {$where}";
    D()->raw_query($sql, $goods_id, $userid_or_sessid);
    return D()->affected_rows();
  }
  
  /**
   * 添加商品到购物车
   * 
   * @param $goods_id integer
   * @param $num integer
   * @param $user_id integer
   * @return array
   *   ['code' => >0, 'msg' => '添加成功']        //这时ret['code']即rec_id
   *   ['code' => -1, 'msg' => '对应商品不存在']
   *   ['code' => -2, 'msg' => '商品库存不足']
   *   ['code' =>-10, 'msg' => '添加失败']
   */
  public static function addToCart($goods_id, $num = 1, $user_id = NULL) {
    if (!$user_id) $user_id = $GLOBALS['user']->ec_user_id;
    
    $ret = ['code' => 0, 'msg' => '添加成功'];
    $goods_info = self::getGoodsInfo($goods_id, []);
    if (!empty($goods_info)) {
      $sess_id = session_id();
      if (!Member::checkECUserExist($user_id)) {
        $user_id = 0;
      }
      
      $userid_or_sessid = $user_id ?  : $sess_id;
      $num = $num > 0 ? intval($num) : 1;
      if ($num > $goods_info['goods_number'] || self::getUserCartNum($userid_or_sessid,$goods_id)>=$goods_info['goods_number']) {
        $ret = ['code' => -2, 'msg' => '商品库存不足'];
        return $ret;
      }
      $cart_rec_id = self::checkCartGoodsExist($userid_or_sessid, $goods_id);
      if ($cart_rec_id) { //商品已经在购物车中存在，则直接将购买数+1
        if (self::changeCartGoodsNum($userid_or_sessid, $cart_rec_id, $num, true, false)) {
          $ret['code'] = $cart_rec_id;
          $ret['added_num'] = $num;
        }
        else {
          $ret = ['code' => -10, 'msg' => '添加失败'];
        }
        return $ret;
      }
      else { //商品没在购物车中，需新加入
        $ecdata = [
          'user_id'      => $user_id,
          'session_id'   => $sess_id,
          'goods_id'     => $goods_id,
          'goods_sn'     => $goods_info['goods_sn'],
          'product_id'   => 0,
          'goods_name'   => $goods_info['goods_name'],
          'market_price' => $goods_info['market_price'],
          'goods_price'  => $goods_info['shop_price'],
          'goods_number' => $num,
          'goods_thumb'  => $goods_info['goods_thumb'],
          'goods_img'    => $goods_info['goods_img'],
          'goods_attr'   => '',
          'is_real'      => $goods_info['is_real'],
          'extension_code'=>$goods_info['extension_code'],
          'parent_id'    => 0,
          'rec_type'     => 0,
          'is_gift'      => 0,
          'is_shipping'  => $goods_info['is_shipping'],
          'can_handsel'  => 0,
          'goods_attr_id'=> '',
        ];
        $cart_rec_id = D()->insert(ectable('cart'), $ecdata, 1, TRUE);
        if ($cart_rec_id) {
          $ret['code'] = $cart_rec_id;
          $ret['added_num'] = $num;
        }
        else {
          $ret = ['code' => -10, 'msg' => '添加失败'];
        }
        return $ret;
      }
    }
    else {
      $ret = ['code' => -1, 'msg' => '对应商品不存在'];
    }
    return $ret;
  }
  
  /**
   * 删除购物车中的商品
   * 
   * @param $rec_ids mixed(array or integer)
   * @param $user_id
   * @return array
   *   ['code'=>  0,'msg'=>'没有要删除的记录']
   *   ['code'=> >0,'msg'=>'删除成功']
   *   ['code'=> -1,'msg'=>'删除失败']
   */
  public static function deleteCartGoods($rec_ids, $user_id) {
    $ret = ['code'=>0,'msg'=>'没有要删除的记录'];
    if (empty($rec_ids)) {
      return $ret;
    }
    if (!is_array($rec_ids)) {
      $rec_ids = [$rec_ids];
    }
    
    $ectb = ectable('cart');
    $where_user = self::getCartOwnerSql($user_id);
    $where_ids  = "'".implode("','", $rec_ids)."'";
    $sql  = "DELETE FROM {$ectb} WHERE `rec_id` IN({$where_ids}) AND {$where_user}";
    D()->raw_query($sql,$user_id);
    $effrows = D()->affected_rows();
    if ($effrows) {
      $ret = ['code'=>$effrows,'msg'=>'删除成功'];
    }
    else {
      $ret = ['code'=>-1,'msg'=>'删除失败'];
    }
    return $ret;
  }
  
  
  public static function getOrderGoods($cart_rec_ids, $userid_or_sessid = NULL, &$total_price = NULL) {
    if (!is_array($cart_rec_ids)) {
      $cart_rec_ids = [$cart_rec_ids];
    }
    if (empty($cart_rec_ids)) {
      return [];
    }
    
    if (!isset($userid_or_sessid)) {
      $userid_or_sessid = $GLOBALS['user']->ec_user_id;
      if (!$userid_or_sessid) $userid_or_sessid = session_id();
    }
    
    $ectb = ectable('cart');
    $where_user = self::getCartOwnerSql($userid_or_sessid);
    $where_ids  = "'".implode("','", $cart_rec_ids)."'";
    $sql = "SELECT * FROM {$ectb} WHERE `rec_id` IN({$where_ids}) AND {$where_user}";
    $ret = D()->raw_query($sql,$userid_or_sessid)->fetch_array_all();
    if (!empty($ret)) {
      $total_price = 0;
      foreach ($ret As &$g) {
        $g['goods_url']   = self::goods_url($g['goods_id']);
        $g['goods_thumb'] = self::goods_picurl($g['goods_thumb']);
        $g['goods_img']   = self::goods_picurl($g['goods_img']);
        $total_price += $g['goods_price']*$g['goods_number'];
      }
    }
    return empty($ret) ? [] : $ret;
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
}
 
/*----- END FILE: class.Goods.php -----*/