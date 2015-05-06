<?php defined('IN_SIMPHP') or die('Access Denied');?>
<nav id="nav-1" class="nav no-bounce">
 <a href="<?=$contextpath?>" class="cur" rel="home"><em>首页</em></a>
 <a href="#/explore" class="" rel="explore"><em>宝贝</em></a>
 <a href="<?=$contextpath?>user" class="" rel="user"><em>我的</em></a>
 <a href="http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=205641974&idx=1&sn=d21c0b265b021ce6e6f9b693551d83b1#rd" class="" rel="about"><em>关于</em></a>
</nav>

<!-- 商品详情页 -->
<nav id="nav-5" class="nav no-bounce hide">
	<div>
		<a href="javascript:void(0);" class="abtn collectbtn">收藏</a>
		<a href="javascript:void(0);" class="abtn buybtn">购买</a>    
	</div>
</nav>

<nav id="nav-6" class="nav no-bounce hide">
	<div>
		<a href="javascript:void(0);" class="abtn paybtn">确认并付款</a>
	</div>
</nav>

<!-- 微信操作提示 -->
<div id="cover-wxtips" class="cover"><img alt="" src="<?=$contextpath;?>themes/mobiles/img/guide.png"/></div>

<script>function nav_show(nav_no, nav, nav_second) {
	if (nav_no===undefined) nav_no = 1;
	
	var $thenav = $('#nav-'+nav_no);
	$('a', $thenav).removeClass('cur');
	$('a[rel='+nav+']', $thenav).addClass('cur');
	$('.nav').hide();
	$thenav.show();

	nav_no = parseInt(nav_no);
	F.pagenav_height = $thenav.height();
	F.log('nav_show: F.pagenav_height='+F.pagenav_height);
	
	switch (nav_no) {
	case 1:
		if (''==gUser.nickname || ''==gUser.logo) {
			$('a[rel=user]', $thenav).attr('href','/user');
		}else{
			$('a[rel=user]', $thenav).attr('href','#/user');
		}
		break;
	case 2:
		break;
	case 3:
		break;
	case 4:
		break;
	}
	
	return false;
}
function nav_hide(nav_no) {
	if (nav_no===undefined) no = 0;
	nav_no = parseInt(nav_no);
	if (0===nav_no) $('.nav').hide();
	else $('#nav-'+nav_no).hide();
	return false;
}
function toEditText(id) {
	F.placeCaretAtEnd(document.getElementById(id));
	return false;
}
</script>