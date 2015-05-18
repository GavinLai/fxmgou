<?php defined('IN_SIMPHP') or die('Access Denied');?>

<?php if(isset($nav_flag1) && 'explore'==$nav_flag1):?>

<nav class="topnav">
  <div class="listyle"><a href="javascript:;" onclick="change_list_style(this)"><i class="micon"></i></a></div>
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

<?php elseif (isset($nav_flag1) && 'cart'==$nav_flag1):?>

<nav class="topnav topnav-cart clearfix" id="topnav-cart">
  <a class="c-3-1<?php if('cartlist'==$nav_flag2):echo ' on';endif;?>" href="<?php echo U('trade/cart/list')?>">购物车</a>
  <a class="c-3-1<?php if('buyrecord'==$nav_flag2):echo ' on';endif;?>" href="<?php echo U('trade/buyrecord')?>">购买记录</a>
  <a class="c-3-1" href="<?php echo U('explore')?>">返回购买</a>
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