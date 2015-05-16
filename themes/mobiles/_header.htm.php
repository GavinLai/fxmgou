<?php defined('IN_SIMPHP') or die('Access Denied');?>

<?php if('explore'==$nav_flag1):?>

<nav class="topnav">
  <div class="listyle"><a href="javascript:;" class="" onclick="change_list_style(this)"><i class="micon"></i></a></div>
  <div class="mbar clearfix">
    <a href="javascript:;" class="fl on">价格从低到高<b class="triangle"></b></a>
    <a href="javascript:;" class="fr">筛选<b class="triangle"></b></a>
    <a href="javascript:;">新品</a>
  </div>
</nav>
<script>
function change_list_style(obj) {
	if ($(obj).hasClass('ls2')) {
		$(obj).removeClass('ls2');
		$('#listyle-2').hide();
		$('#listyle-1').show();
	} else {
		$(obj).addClass('ls2');
		$('#listyle-1').hide();
		$('#listyle-2').show();
	}
	F.set_scroller(false,0);
}
</script>

<?php elseif ('cart'==$nav_flag1):?>

<nav class="topnav topnav-cart clearfix" id="topnav-cart">
  <a class="c-3-1<?php if('cartlist'==$nav_flag2):echo ' on';endif;?>" href="<?=$contextpath?>trade/cart/list">购物车</a>
  <a class="c-3-1<?php if('buyrecord'==$nav_flag2):echo ' on';endif;?>" href="<?=$contextpath?>trade/buyrecord">购买记录</a>
  <a class="c-3-1" href="<?=$contextpath?>">返回首页</a>
</nav>
<script>
$(function(){
	$('#topnav-cart a').click(function(){
		$('#topnav-cart a').removeClass('on');
		$(this).addClass('on');
	});
});
</script>

<?php endif;?>