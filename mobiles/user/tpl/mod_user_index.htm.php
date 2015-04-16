<?php defined('IN_SIMPHP') or die('Access Denied');?>
  <script>gData.referURI='/';</script>
  <div class="timg" style="margin-top:5px;">
    <div>
    <img src="<?=$userInfo['logo']?>" alt="" /><br/>
    <?=$userInfo['nickname']?>
    </div>
  </div>
  <div class="tlist clearfix">
    <a href="#/"><i class="i1"></i><span>首页</span></a>
  </div>
  <div class="tlist clearfix">
    <a href="#/user/collect"><i class="i2"></i><span>收藏</span></a>
  </div>
  <div class="tlist clearfix" style="display:none">
    <i class="i3"></i><span>邀请</span>
  </div>
  <div class="tlist clearfix">
  	<a href="#/user/feedback"><i class="i4"></i><span>反馈</span></a>
  </div>
  <div class="tlist clearfix" style="display:none">
    <a href="#/user/setup"><i class="i5"></i><span>设置</span></a>
  </div>
<?php include T($tpl_footer);?>