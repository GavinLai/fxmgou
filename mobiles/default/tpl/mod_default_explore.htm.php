<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<div class="block block2" id="listyle-1">
  <ul class="liset">
  <?php foreach($goods_latest AS $it):?>
    <li class="liit">
      <a href="<?php echo U('item/'.$it['goods_id'])?>">
        <img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" />
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
      <a href="<?php echo U('item/'.$it['goods_id'])?>" class="clearfix">
        <div class="left"><img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" /></div>
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
<?php include T($tpl_footer);?>