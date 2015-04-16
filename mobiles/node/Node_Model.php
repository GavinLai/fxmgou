<?php
/**
 * Node Model
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

class Node_Model extends Model {
  
  /**
   * Get node category list
   * @param boolean $all
   * @return array
   */
  public static function getCategoryList($all = FALSE) {
    
    $where = 'AND `available`=1';
    if ($all) {
      $where = '';
    }
    $result = D()->query("SELECT * FROM {category} WHERE 1 {$where} ORDER BY `sortorder` DESC,`cate_id` ASC")
                 ->fetch_array_all();
    return $result;
  }
  
  public static function getNodeList($cate_id = 0, $type_id = 'word', $orderby = 'created', $order = 'DESC', $tag_id=0, $limit=30) {
    
    // Parameters checking
    $cate_id  = intval($cate_id);
    $type_ids = self::getTypeIdSet();
    if (!in_array($type_id, $type_ids)) {
      $type_id = '';
    }
    $order = strtoupper($order);
    if (!in_array($order, ['DESC','ASC'])) {
      $order = 'DESC';
    }
    if (!in_array($orderby, ['created','changed','browsecnt','votecnt','collectcnt','sharecnt'])) { //limit order fields
      $orderby = 'created';
    }
    
    $where = '';
    if ($cate_id) {
      $where .= " AND n.`cate_id`={$cate_id}";
    }
    if ($type_id != '') {
      $where .= " AND n.`type_id`='{$type_id}'";
    }
    if($tag_id){
      $where .= "  AND t.`tag_id`={$tag_id} ";
    }
    $where .= " AND `status`='R'";
    
    $sql = '';
    if ($type_id == '' || $type_id == 'word') {
      $sql = "SELECT n.* FROM {node} n";
      if($tag_id){
        $sql = "SELECT n.* FROM {node} n INNER JOIN {tag_node} t ON n.nid=t.nid ";
      }
    }
    else {
      $sql = "SELECT n.*,tn.* FROM {node} n INNER JOIN {node_{$type_id}} tn ON n.nid=tn.tnid";
      if($tag_id){
        $sql = "SELECT n.*,tn.* FROM {node} n INNER JOIN {node_{$type_id}} tn ON n.nid=tn.tnid INNER JOIN {tag_node} t ON n.nid=t.nid ";
      }
    }
    $sql .= " WHERE 1 {$where} ORDER BY {$orderby} {$order}";
    $sql_cnt = "SELECT COUNT(n.nid) FROM {node} n WHERE 1 {$where}";
    
    $result = D()->pager_query($sql,$limit,$sqlcnt,0)->fetch_array_all();
    return $result;
  }
  
  public static function getRandNode($limit=10){
    $sql = "SELECT * FROM {node} WHERE `status`='R' ORDER BY RAND()  LIMIT %d ";
    return D()->query($sql, $limit)->fetch_array_all();
  }

  public static function getRecommendNode($limit=10){
    $timestamp = strtotime('-3 month');
    $sql = "SELECT * FROM {node} WHERE `status`='R' AND recommend<>0 AND changed>{$timestamp} ORDER BY `sort` DESC,recommend DESC";
    $sqlcnt = "SELECT count(nid) FROM {node} WHERE `status`='R' AND recommend=1 AND changed>{$timestamp} ";
    return D()->pager_query($sql, $limit, $sqlcnt, 0)->fetch_array_all();
  }

  /**
   * Get node info
   * @param integer $nid
   * @return array all fields' info of the node or false when $nid invalid
   */
  public static function getNodeInfo($nid) {
    if (!$nid) return false;
    $row = D()->get_one("SELECT * FROM {node} WHERE `nid`=%d", $nid);
    if (!empty($row)) {
      $type_id = $row['type_id'];
      if ($type_id!='word') {
        $row_ext = D()->get_one("SELECT * FROM {node_{$type_id}} WHERE `tnid`=%d",$nid);
        $row = array_merge($row, $row_ext);
      }
      return $row;
    }
    return false;
  }
  public static function getNodeTinyInfo($nid){
    if (!$nid) return false;
    $row = D()->get_one("SELECT * FROM {node} WHERE `nid`=%d ", $nid);
    return $row;
  }
  
  /**
   * Get type id set
   * @return array
   */
  public static function getTypeIdSet() {
    static $_cdata = [];
    if (empty($_cdata)) {
      $_cdata = D()->query("SELECT `type_id` FROM {type} WHERE `available`=1")->fetch_column('type_id');
    }
    return $_cdata;
  }
  /**
   * 
   * @param array $card 
   * @return [type] [description]
   */
  public static function saveCard($card){
    $nuid = D()->insert('node_user', $card);
    if($card['status']=='R'){
      D()->query("UPDATE {node} SET sharecnt=sharecnt+1 WHERE nid=%d", $card['nid']);
    }
    return $nuid;
  }

  public static function vote($nid, $uid){
    $affected_rows = D()->query("UPDATE {node} SET votecnt=votecnt+1 WHERE nid=%d ", $nid)->affected_rows();
    if($affected_rows>0){
      $nodeInfo = self::getNodeTinyInfo($nid);
      D()->insert('action', ['uid'=>$uid, 'nid'=>$nid, 'action'=>'love', 'type_id'=>$nodeInfo['type_id'], 'timeline'=>time()]);
    }
    return $affected_rows;
  }

  public static function cancleVote($nid, $uid){
    $affected_rows = D()->query("UPDATE {node} SET votecnt=votecnt-1 WHERE nid=%d ", $nid)->affected_rows();
    if($affected_rows){
      D()->delete('action', ['nid'=>$nid, 'uid'=>$uid]);
    }
    return $affected_rows;
  }

  public static function collect($nid, $uid){
    $affected_rows = D()->query("UPDATE {node} SET collectcnt=collectcnt+1 WHERE nid=%d ", $nid)->affected_rows();
    if($affected_rows>0){
      $nodeInfo = self::getNodeTinyInfo($nid);
      D()->insert('action', ['uid'=>$uid, 'nid'=>$nid, 'action'=>'collect', 'type_id'=>$nodeInfo['type_id'], 'timeline'=>time()]);
    }
    return $affected_rows; 
  }

  public static function cancleCollect($nid, $uid){
    $affected_rows = D()->query("UPDATE {node} SET collectcnt=collectcnt-1 WHERE nid=%d ", $nid)->affected_rows();
    if($affected_rows){
      D()->delete('action', ['nid'=>$nid, 'uid'=>$uid]);
    }
    return $affected_rows;
  }

  public static function actionRecord($nid, $uid, $action){
    $record = D()->get_one("SELECT * FROM {action} WHERE uid=%d AND nid=%d AND action='%s' ", $uid, $nid, $action);
    if($record != false){
      return true;
    }
    return false;
  }
  public static function getUserCardById($nuid){
    return D()->get_one("SELECT * FROM {node_user} WHERE nuid=%d", $nuid);
  }

  public static function getShareHistory($uid, $limit=10){
    $sql = "SELECT nu.*,n.type_id,c.cate_name,t.type_name FROM {node_user} AS nu ,{node} AS n,{category} AS c,{type} AS t WHERE nu.uid=%d AND nu.status='R' AND nu.nid=n.nid  AND n.cate_id=c.cate_id  AND n.type_id=t.type_id  ORDER BY nu.timeline DESC";
    $sqlcnt = "SELECT COUNT(nu.nuid) FROM {node_user} AS nu ,{node} AS n,{category} AS c,{type} AS t WHERE nu.uid=%d AND nu.status='R' AND nu.nid=n.nid  AND n.cate_id=c.cate_id  AND n.type_id=t.type_id  ";
    $recordes = D()->pager_query($sql,$limit,$sqlcnt, 0, [$uid])->fetch_assoc_all();
    return $recordes;
  }

  public static function getShareHistoryByNuid($nuid,$uid){
    $sql = "SELECT * FROM {node_user} WHERE nuid=%d AND uid=%d";
    $record = D()->get_one($sql, $nuid, $uid);
    return $record;
  }


  public static function updateShare($nuid, $uid, $nid){
    $affected_rows = D()->update('node_user', ['status'=>'R'], ['nuid'=>$nuid,'uid'=>$uid]);
    if($affected_rows>0){
      D()->query("UPDATE {node} SET sharecnt=sharecnt+1 WHERE nid=%d", $nid);
      return true;
    }else{
      return false;
    }
  }
  public static function getTagByCate($cate_id){
    $sql = "SELECT tc.*,t.tag_name FROM {tag_cate} AS tc,{tag} AS t WHERE tc.cate_id=%d AND tc.tag_id=t.tag_id ORDER BY rank DESC";
    return D()->query($sql,$cate_id)->fetch_array_all();
  }

  public static function updateGiftCount($nid, $num){
    $sql = "UPDATE {node_gift} SET count=count-%d WHERE tnid=%d AND count>=%d";
    $affected = D()->query($sql, $num, $nid, $num)->affected_rows();
    if($affected>0){
      return TRUE;
    }else{
      return FALSE;
    }
  }
  /**
   * 关键词检索
   * @param  [type]  $keywords [description]
   * @param  [type]  $limit    [description]
   * @param  integer $mode     [description]  0:检索search字段,1:检索title字段
   * @return [type]            [description]
   */
  public static function searchNode($keywords, $limit, $mode=0){
    
    if($mode==0){
      $sql = "SELECT * FROM {node} WHERE `status`='R' AND search LIKE '%{$keywords}%' ORDER BY `sort` DESC,changed DESC";
      $sqlcnt = "SELECT count(nid) FROM {node} WHERE `status`='R' AND search LIKE '%{$keywords}%' ";
    }elseif($mode==1){
      $sql = "SELECT * FROM {node} WHERE `status`='R' AND title LIKE '%{$keywords}%' ORDER BY `sort` DESC,changed DESC";
      $sqlcnt = "SELECT count(nid) FROM {node} WHERE `status`='R' AND title LIKE '%{$keywords}%' ";
    }

    return D()->pager_query($sql, $limit, $sqlcnt, 0)->fetch_array_all();
  }
  
  /**
   * 获取node feed list
   * @param array $searchcond
   * @param integer $page
   * @param integer $limit
   * @param boolean $hasmore, 引用参数，用于传出是否还有下一页记录
   * @return array
   */
  public static function getFeedList(Array $searchcond = [],$page = 1, $limit = 10, &$hasmore = false) {
    $limit = $limit>0 ? $limit : 10;
    $start = $page<1? 0 : ($page-1)*$limit;
    
    $limit_true = $limit+1; //+1是为了多拿一个以判断是否还有分页数据
    
    $where = '1';
    if (!empty($searchcond['cate_id'])) {
      $where .= ' AND `cate_id`='.$searchcond['cate_id'];
    }
    $sql = "SELECT * FROM {node} WHERE {$where} AND `status`='R' ORDER BY `recommend` DESC,`changed` DESC LIMIT {$start},{$limit_true}";
    
    //当前结果集
    $result = D()->query($sql)->fetch_array_all();
    
    //检查是否有更多
    $hasmore = false;
    if (!empty($result) && count($result)>$limit) {
      $hasmore = true;
      array_pop($result);	//将最后一个用于判断分页数据的元素丢弃
    }
    
    //补齐必要的字段
    $user = Member::getUser();
    $uid = empty($user['uid']) ? 0 : $user['uid'];
    foreach($result AS &$rs) {
      if ('word'!=$rs['type_id']) {
        $rs_extra = D()->get_one("SELECT * FROM `{node_%s}` WHERE `tnid`=%d", $rs['type_id'], $rs['nid']);
        $rs = array_merge($rs,$rs_extra);
      }
      $rs['collect'] = self::actionRecord($rs['nid'], $uid, 'collect');
      $rs['love'] = self::actionRecord($rs['nid'], $uid, 'love');
    }
    
    return $result;
  }
}
 
/*----- END FILE: Node_Model.php -----*/