<?php defined('IN_SIMPHP') or die('Access Denied');?>
<nav id="nav-1" class="nav no-bounce">
 <a href="<?=$contextpath?>" <?php if('home'==$nav):?>class="cur"<?php endif;?> rel="home"><em>首页</em></a>
 <a href="<?=$contextpath?>explore" <?php if('explore'==$nav):?>class="cur"<?php endif;?> rel="explore"><em>宝贝</em></a>
 <a href="<?=$contextpath?>user/" <?php if('user'==$nav):?>class="cur"<?php endif;?> rel="user"><em>我的</em></a>
 <a href="http://mp.weixin.qq.com/s?__biz=MzAwNjQyNzA2NA==&mid=205641974&idx=1&sn=d21c0b265b021ce6e6f9b693551d83b1#rd" rel="about"><em>关于</em></a>
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

<script type="text/javascript">
$('.nav a').click(function(){
	$(this).parent().find('a').removeClass('cur');
	$(this).addClass('cur');
});
</script>