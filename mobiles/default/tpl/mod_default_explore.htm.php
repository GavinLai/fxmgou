<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<nav class="topnav">
  <div class="listyle"><a href="javascript:;" class="" onclick="return change_list_style(this)"><i class="micon"></i></a></div>
  <div class="mbar clearfix">
    <a href="javascript:;" class="fl on">价格从低到高<b class="triangle"></b></a>
    <a href="javascript:;" class="fr">筛选<b class="triangle"></b></a>
    <a href="javascript:;">新品</a>
  </div>
</nav>

<div class="block block2" id="listyle-1">
  <ul class="liset">
  <?php foreach($goods_latest AS $it):?>
    <li class="liit">
      <a href="#/item/<?=$it['goods_id']?>">
        <img src="<?php echo emptyimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" />
        <h3 class="gt"><?=$it['goods_name']?></h3>
        <p class="gp"><em>￥<?=$it['shop_price']?></em><span class="tip">1人付款</span><span class="dmore">...</span></p>
      </a>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

<div class="block block3" id="listyle-2">
  <ul class="liset">
  <?php foreach($goods_latest AS $it):?>
    <li class="bbsizing liit">
      <a href="#/item/<?=$it['goods_id']?>" class="clearfix">
        <div class="left"><img src="<?php echo emptyimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" /></div>
        <div class="right">
          <h3 class="gt"><?=$it['goods_name']?></h3>
          <p class="gp"><em>￥<?=$it['shop_price']?></em></p>
          <p class="gbtm"><span class="tip">共售1笔</span><span class="dmore">...</span></p>
        </div>
      </a>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

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
}
</script>