<?php defined('IN_SIMPHP') or die('Access Denied');?>
<div id="right-icon" class="no-text hide">
  <a href="<?php echo U('trade/cart/list')?>" id="global-cart"><?=$user->ec_cart_num?></a>
</div>

<?php if ((!isset($no_display_cart) || !$no_display_cart) && $user->ec_cart_num):?>
<script>$(function(){$('#right-icon').show();});</script>
<?php endif?>

<?php if ($nav_no==1):?>

<nav id="nav-1" class="nav no-bounce">
 <div class="nav-it"><a href="<?php echo U()?>" <?php if('home'==$nav_flag1):?>class="cur"<?php endif;?> rel="home">首页</a></div>
 <div class="nav-it"><a href="<?php echo U('explore')?>" <?php if('explore'==$nav_flag1):?>class="cur"<?php endif;?> rel="explore">宝贝</a></div>
 <div class="nav-it"><a href="<?php echo U('user')?>" <?php if('user'==$nav_flag1):?>class="cur"<?php endif;?> rel="user">我的</a></div>
 <div class="nav-it"><a href="http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=205641974&idx=1&sn=d21c0b265b021ce6e6f9b693551d83b1#rd" rel="about">关于</a></div>
</nav>
<script>
$('.nav a').click(function(){
	$(this).parents('.nav').find('a').removeClass('cur');
	$(this).addClass('cur');
});
</script>

<?php elseif($nav_no==2):/*To: if ($nav_no==1)*/?>

<nav id="nav-2" class="nav nav-<?=$nav_no?> nav-<?=$nav_flag1?> no-bounce">
  <div class="nav-body clearfix">
<?php if ('item'==$nav_flag1):?>
    <div class="nav-it"><a href="<?php echo U('explore')?>" class="btn">☜返回</a></div>
    <div class="nav-it"><a href="javascript:void(0);" class="btn">收藏</a></div>
    <div class="nav-it"><a href="javascript:void(0);" class="btn btn-orange" id="btn-add-to-cart" data-goods_id="<?=$the_goods_id?>">加入购物车</a></div>
<?php elseif ('cart'==$nav_flag1):?>
    <div class="c-lt checked" id="cart-checkall"><span class="check checked"></span>全选</div>
    <div class="c-rt">
      <button class="btn btn-orange" id="cart-btncheckout">结账<span>(0)</span></button>
      <button class="btn btn-red hide" id="cart-btndelete" disabled="disabled">删除</button>
    </div>
    <div class="c-md" id="cart-totalwrap">合计：<span id="cart-totalprice">0</span>元</div>
<?php endif;?>
  </div>
</nav>
<script>
$(function(){

<?php if ('item'==$nav_flag1):?>
$('#btn-add-to-cart').click(function(){
	var _this = $(this);
	if (_this.attr('data-ajaxing')=='1') return;
	_this.attr('data-ajaxing', 1);
	var goods_id = _this.attr('data-goods_id'),
	    goods_num= 1;
	F.post('/trade/cart/add', {goods_id:goods_id,goods_num:goods_num}, function(ret){
		_this.attr('data-ajaxing', 0);
		if (ret.code > 0) {
			$('#global-cart').text(ret.cart_num);
			$('#right-icon').show();
			var old_stock = parseInt($('#stock-num').text());
			old_stock -= ret.added_num;
			$('#stock-num').text(old_stock);
			alert(ret.msg);
		}else{
			alert(ret.msg);
		}
	});
});
<?php endif;?>

}); //END $(function(){
</script>

<?php endif;/*End: elseif($nav_no==2)*/?>

<!-- 微信操作提示 -->
<div id="cover-wxtips" class="cover"><img alt="" src="<?=$contextpath;?>themes/mobiles/img/guide.png"/></div>