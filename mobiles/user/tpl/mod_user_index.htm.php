<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<div class="timg" style="margin-top:5px;">
  <div>
  <img src="<?=$userInfo['logo']?>" alt="" /><br/>
  <?=$userInfo['nickname']?>
  </div>
</div>
<div class="tlist clearfix">
  <a href="<?php echo U('user/collect')?>" class="btn"><i class="i2"></i><span>我的收藏</span></a>
</div>
<div class="tlist clearfix">
  <a href="<?php echo U('trade/order/record')?>" class="btn"><i class="i2"></i><span>购买记录</span></a>
</div>
<div class="tlist clearfix">
	<a href="<?php echo U('user/feedback')?>" class="btn"><i class="i4"></i><span>问题反馈</span></a>
</div>
<?php include T($tpl_footer);?>