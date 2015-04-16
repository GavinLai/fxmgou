<?php
/**
 * Mall Model
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Mall_Model extends Model {

	public static function goodsInfo($nid){
		if (!$nid) return false;
	    $row = D()->get_one("SELECT n.*,nt.* FROM {node} AS n LEFT JOIN {node_gift} AS nt ON n.nid=nt.tnid WHERE `nid`=%d ", $nid);
	    return $row;
	}

	public static function saveOrder($order){
		$insert_id = D()->insert('order',$order);
		return $insert_id;
	}

	public static function getOrderByNo($order_no){
		$sql = "SELECT * FROM {order} WHERE order_no='%s' ";
		$order = D()->get_one($sql, $order_no);

		return $order;
	}

	
}