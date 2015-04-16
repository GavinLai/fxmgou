<?php defined('IN_SIMPHP') or die('Access Denied');?>
  <script>gData.referURI='/news';</script>
	<div class="headli">
      	<a href="#/news" class="fl articles">全部文章</a>
        <a href="javascript:void(0);" class="fl shareq"><span>分享朋友圈</span></a>
    </div>
	
	<div class="infor"><img src="<?=$info['img']?>" /></div>
	<div class="inforContent"><?=$info['content']?></div>
    
  <div class="footers"><img src="/misc/images/news/f.jpg" /></div>
<?php include T($tpl_footer);?>