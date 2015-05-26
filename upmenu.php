<?php
/**
 * 更新微信菜单
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
//~ require init.php
require (__DIR__.'/core/init.php');

$json =<<<HEREDOC
{
	"button" : 
	[
		{
			"type" : "view",
			"name" : "小蜜商城",
			"url"  : "http://m.fxmgou.com/"
		},
		{
			"type" : "click",
			"name" : "最新文章",
			"key"  : "100"
		},
		{
			"type" : "click",
			"name" : "关于小蜜",
			"key"  : "101"
		}
	]
}
HEREDOC;

$menuConfig = json_decode($json, TRUE);
config_set('wxmenu', $menuConfig, 'J');

header('Content-Type: text/plain;charset=utf-8');
$msg = '菜单更新失败';
if((new Weixin([],'fxmgou'))->createMenu($menuConfig)){
  $msg = '菜单更新成功';
}

echo $msg;

/*----- END FILE: upmenu.php -----*/