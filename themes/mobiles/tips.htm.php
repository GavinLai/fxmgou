<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
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
	<?php tplholder('HEAD_CSS');?>
</head>
<body>
	<div id="rtWrap" class="bbsizing">
		<header><span id="tips_head" class="marAuto"><img src="/themes/mobiles/img/tips_head.png" alt=""></span></header>
		<p id="tips_word" class="marAuto">请在微信客户端中打开"福小秘" 或 <a href="//www.fxmapp.com" style="color:#34aea3">访问官网</a></p>
		<div id="public_no">
			<span ><img src="/themes/mobiles/img/tips_logo.png" alt="" id="public_no_img"></span>
			<p>微信公众号：fxmapp</p>
		</div>
		<span><img src="/misc/images/qrcode/fxmapp_430.jpg" alt="" id="wechat_img" class="marAuto"></span>
	</div>
</body>
</html><?php
add_css('c.css',['scope'=>'global','nover'=>1]);
add_css('tips.css',['scope'=>'global']);
add_css('pc.css',['scope'=>'global','media'=>'only screen and (min-width: 1025px)']);
?>