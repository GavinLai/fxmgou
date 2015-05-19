<?php defined('IN_SIMPHP') or die('Access Denied');?>

<?php if (!$orders_num):?>

<div class="list-empty">
  <?php if(''==$errmsg):?>
  <h1 class="list-empty-header">居然还没买过东西╮(╯﹏╰）╭</h1>
  <div class="list-empty-content"><a href="<?php echo U('explore')?>">去逛逛</a></div>
  <?php else:?>
  <h1 class="list-empty-header"><?=$errmsg?></h1>
  <?php endif;?>
</div>

<?php else :?>

<?php foreach ($orders AS $ord):?>

<div class="list-container">
  
  <div class="list-head <?php if ($ord['active_order']):echo 'list-head-blue';else:echo 'list-head-gray';endif;?> list-head-order">
    <span class="order-date"><?php echo date('Y-m-d',$ord['add_time'])?></span><span class="order-sn">订单号：<em><?=$ord['order_sn']?></em></span>
  </div>
  
  <div class="list-body list-body-order">
  
  <?php foreach($ord['order_goods'] AS $g):?>
    <div class="it clearfix" data-url="<?=$g['goods_url']?>" data-rid="<?=$g['rec_id']?>">
      <div class="c-24-6 col-2 withclickurl"><img src="<?=$g['goods_thumb']?>" alt="" class="goods_pic" /></div>
      <div class="c-24-11 col-3 withclickurl">
        <p><?=$g['goods_name']?></p>
        <p class="price-txt">(￥<?=$g['goods_price']?> x<?=$g['goods_number']?>)</p>
      </div>
    </div>
  <?php endforeach;?>
    <div class="right-merge">
      <p>￥<span class="gprice"><?=$ord['order_amount']?></span></p>
      <div class="order-status"><?=$ord['show_status_html']?></div>
    </div>
    
  </div>
  
</div>

<?php endforeach;?>

<?php form_topay_script(U('trade/order/record'));?>

<script>
$(function(){
	var $lbod = $('.list-body');
	var thisctx = {};
	
	$('.withclickurl',$lbod).click(function(){
		window.location.href = $(this).parent().attr('data-url');
		return false;
	});
	$('.btn-order-cancel',$lbod).click(function(){
		if (typeof(thisctx.ajaxing_cancel)=='undefined') {
			thisctx.ajaxing_cancel = 0;
		}
		if (thisctx.ajaxing_cancel) return false;
		thisctx.ajaxing_cancel = 1;

		if (confirm('确定取消该订单么？')) {
  		var pdata = {"order_id": parseInt($(this).attr('data-order_id'))};
  		F.post('<?php echo U('trade/order/cancel')?>',pdata,function(ret){
  			thisctx.ajaxing_cancel = undefined;
  			if (ret.flag=='SUC') {
  				window.location.reload();
  			}
  		});
		}
		return false;
	});
	$('.btn-order-topay',$lbod).click(function(){
		form_topay_submit($(this).attr('data-order_id'));
	});
});
</script>

<?php endif;?>