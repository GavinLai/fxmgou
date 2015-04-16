<?php defined('IN_SIMPHP') or die('Access Denied');?>
    <script>gData.referURI='/user';</script>
	  <div class="setm">
    	<dl class="clearfix">
      	<dt><img src="<?=$user['logo']?>" /></dt>
        <dd><?=$user['nickname']?><br><!--1983年1月27日--></dd>
      </dl>
    </div>
    <h2 class="my">我的</h2>
    <ul class="mylist">
    	<li><a href="javascript:void(0);"><i class="i1"></i>全部订单<span>&gt;</span></a></li>
        <li><a href="javascript:void(0);"><i class="i2"></i>积分优惠<span>&gt;</span></a></li>
        <li><a href="javascript:void(0);"><i class="i3"></i>收货地址<span>&gt;</span></a></li>
    </ul>
    <h2 class="my pt30">账号</h2>
    <ul class="mylist">
    	<li><a href="javascript:void(0);"><i class="i4"></i>新浪微博<span>&gt;</span></a></li>
        <li><a href="javascript:void(0);"><i class="i5"></i>腾讯微博<span>&gt;</span></a></li>
        <li><a href="javascript:void(0);"><i class="i6"></i>人人网<span>&gt;</span></a></li>
        <li><a href="javascript:void(0);"><i class="i7"></i>QQ<span>&gt;</span></a></li>
    </ul>
<?php include T($tpl_footer);?>