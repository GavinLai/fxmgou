<?php
/**
 *  News Model
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class News_Model extends Model {
	public static function getList($orderby = 'created', $order = 'DESC', $limit=30) {
	    $order = strtoupper($order);
	    if (!in_array($order, ['DESC','ASC'])) {
	      $order = 'DESC';
	    }
	    if (!in_array($orderby, ['created','changed', 'sort'])) { //limit order fields
	      $orderby = 'created';
	    }
	    
	    $where = '';
	    $where .= " AND `status`='R'";
	    
	    $sql = 'SELECT * FROM {news} ';
	    $sql .= " WHERE 1 {$where} ORDER BY {$orderby} {$order}";
	    $sql_cnt = "SELECT COUNT(a.nid) FROM {news} a WHERE 1 {$where}";
	    
	    $result = D()->pager_query($sql,$limit,$sqlcnt,0)->fetch_array_all();
	    return $result;
  	}

  	public static function getNewsBynid($nid){
	  	$sql = "SELECT * FROM {news} WHERE nid=%d AND `status`='R' ";
	  	return D()->get_one($sql, $nid);
  	}

  	public static function getRecommend($limit=1){
  		$sql = "SELECT * FROM {news} WHERE recommend=1 AND status='R' ORDER BY sort DESC, nid DESC ";
  		return D()->query($sql)->fetch_array_all();
  	}

}