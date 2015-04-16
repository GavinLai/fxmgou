<?php defined('IN_SIMPHP') or die('Access Denied');?><?php $_qpath=Request::path();if($_qpath!='' && $_qpath!='/') Response::redirect('/#'.$_qpath);?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title><?php echo L('appname')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $seo['keyword']?>">
<meta name="description" content="<?php echo $seo['desc']?>">
<meta name="author" content="Donglong Technical Team">
<meta name="apple-mobile-web-app-title" content="<?php echo L('appname')?>">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<!-- Android -->
<meta name="mobile-web-app-capable" content="yes">
<link rel="shortcut icon" sizes="196x196" href="<?=$contextpath;?>misc/images/napp/touch-icon-196.png" />
<link rel="shortcut icon" sizes="152x152" href="<?=$contextpath;?>misc/images/napp/touch-icon-152.png" />
<link rel="shortcut icon" sizes="144x144" href="<?=$contextpath;?>misc/images/napp/touch-icon-144.png" />
<link rel="shortcut icon" sizes="128x128" href="<?=$contextpath;?>misc/images/napp/touch-icon-128.png" />
<link rel="shortcut icon" sizes="120x120" href="<?=$contextpath;?>misc/images/napp/touch-icon-120.png" />
<link rel="shortcut icon" sizes="114x114" href="<?=$contextpath;?>misc/images/napp/touch-icon-114.png" />
<link rel="shortcut icon" sizes="76x76" href="<?=$contextpath;?>misc/images/napp/touch-icon-76.png" />
<link rel="shortcut icon" sizes="72x72" href="<?=$contextpath;?>misc/images/napp/touch-icon-72.png" />
<link rel="shortcut icon" sizes="60x60" href="<?=$contextpath;?>misc/images/napp/touch-icon-60.png" />
<link rel="shortcut icon" sizes="57x57" href="<?=$contextpath;?>misc/images/napp/touch-icon-57.png" />
<link rel="shortcut icon" href="<?=$contextpath;?>favicon.ico" type="image/x-icon" />
<link rel="icon" href="<?=$contextpath;?>favicon.ico" type="image/x-icon" />
<!-- iOS -->
<link rel="apple-touch-icon" sizes="57x57" href="<?=$contextpath;?>misc/images/napp/touch-icon-57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="<?=$contextpath;?>misc/images/napp/touch-icon-60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?=$contextpath;?>misc/images/napp/touch-icon-72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="<?=$contextpath;?>misc/images/napp/touch-icon-76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?=$contextpath;?>misc/images/napp/touch-icon-114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="<?=$contextpath;?>misc/images/napp/touch-icon-120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="<?=$contextpath;?>misc/images/napp/touch-icon-144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="<?=$contextpath;?>misc/images/napp/touch-icon-152.png" />
<script>var APPNAME='<?php echo L('appname')?>';</script>   
<?php tplholder('HEAD_CSS');?>
<?php tplholder('HEAD_JS');?>
</head>
<body>
<div id="rtWrap">
  <?php include T($tpl_header);?>
  <div id="activePage"><section class="scrollArea"></section><div class="pageBg"><span>下拉出现搜索页</span></div></div>
  <div id="loadingCanvas"></div>
  <?php include T('_nav');?>
  <div id="MPicCrop"></div><!-- 用于全屏贺卡编辑 -->
</div>
<?php include T('_popdlg');?>
<?php tplholder('FOOT_JS');?>
<script>var FST=new Object();FST.autostart=0;</script>
<script type="text/javascript" src="<?=$contextpath;?>misc/js/fst.min.js"></script>
</body>
</html><?php
//add css file
add_css('c.min.css,external.min.css',['scope'=>'global','ver'=>1]);
add_css('m.css',['scope'=>'global']);
add_css('pc.css',['scope'=>'global','media'=>'only screen and (min-width: 1025px)']);
//add js file
add_js('ext/jquery-2.1.1.min.js',['pos'=>'head','ver'=>'none']);
add_js('ext/jquery.tinyjqm.min.js,ext/touchslider.min.js,fm.min.js',['pos'=>'head','ver'=>1]);
add_js('m.js',['pos'=>'foot']);
?>