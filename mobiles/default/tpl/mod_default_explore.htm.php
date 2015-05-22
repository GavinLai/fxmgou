<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<div class="block block2" id="listyle-1">
  <ul class="liset">
  <?php foreach($goods_list AS $it):?>
    <li class="liit">
      <a href="<?php echo U('item/'.$it['goods_id'])?>">
        <img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" />
        <h3 class="gt"><?=$it['goods_name']?></h3>
        <p class="gp">
          <em>￥<?=$it['shop_price']?></em>
          <span class="tip">
          <?php if($order=='click'):?>
          <?=$it['click_count']?>查看
          <?php elseif($order=='collect'):?>
          <?=$it['collect_count']?>收藏
          <?php else:?>
          共售<?=$it['paid_order_count']?>笔
          <?php endif;?>
          </span>
          <span class="dmore">...</span>
        </p>
      </a>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

<div class="block block3" id="listyle-2">
  <ul class="liset">
  <?php foreach($goods_list AS $it):?>
    <li class="bbsizing liit">
      <a href="<?php echo U('item/'.$it['goods_id'])?>" class="clearfix">
        <div class="left"><img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" /></div>
        <div class="right">
          <h3 class="gt"><?=$it['goods_name']?></h3>
          <p class="gp"><em>￥<?=$it['shop_price']?></em></p>
          <p class="gbtm">
            <span class="tip">
          <?php if($order=='click'):?>
          查看数: <?=$it['click_count']?>
          <?php elseif($order=='collect'):?>
          收藏数: <?=$it['collect_count']?>
          <?php else:?>
          共售 <?=$it['paid_order_count']?> 笔
          <?php endif;?>
            </span>
            <span class="dmore">...</span>
          </p>
        </div>
      </a>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

<script type="text/html" id="downmenu-html">
<div id="pageCover"></div>
<ul class="bbsizing" id="down-sortmenu">
<?php foreach ($order_set AS $it):?>
  <?php if(!$it['is_show']): continue; endif;?>
  <li class="mit<?php if($it['is_last']): echo ' last';endif;if($order==$it['id']): echo ' on';endif;?>">
    <a href="<?php echo U('explore','zonghe'==$it['id']?'':'o='.$it['id'])?>" rel="<?=$it['id']?>"><?=$it['name']?></a>
    <?php if($order==$it['id']): echo '<span>√</span>';endif;?>
  </li>
<?php endforeach;?>
</ul>
</script>

<?php include T($tpl_footer);?>
<script>F.isDownpullDisplay=false;</script>
<script>
$(function(){
	F.pageactive.append($('#downmenu-html').html());
	$('#pageCover').click(function(){
		$('#topnav-btn-change-order').click();
		return false;
	});
});
</script>


















