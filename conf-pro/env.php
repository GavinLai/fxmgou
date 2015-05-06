<?php
/**
 * 应用环境配置
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

return [
  //[General]
  'env'      => 'pro',
  'version'  => '1.0.0',
  'timezone' => 'Asia/Shanghai',
  'charset'  => 'utf-8',
  'lang'     => 'zh-cn',
  'sitetheme'=> 'default',
  'contextpath' => '/',
  'cleanurl' => 1,
  'usecdn'   => 1,
  'allowed_moddirs' => ['modules','mobiles','admins','apis'],
  //[Log]
  'log_dir' => '/var/tmp',
  //[TemplateSetting]
  'tplclass' => 'PlainTpl',
  'tplpostfix' => '.htm.php',
  'tplcachedir' => '/var/run/tpl',
  'tplcache' => 0,
  'tplcache_expires' => 900,
  'tplcompile_check' => 1,
  'tplforce_compile' => 1,
  'tpldebug' => 1,
  //站点信息
  'site' => [
  	'mobile' => 'www.fxmgou.com',
  	'shop' => 'pc.fxmgou.com',
  ],
  //上传文件保存目录 
  'picsavedir'=>'/a/',
  //平台币兑人民币比率,1元=10平台币
  'rmb_platform'=>10,
  //是否与UC同步
  'uc_sync'=>1,
  //数据加密key
  'au_key'=>'WIEJEKI1349*&^%234KKiekeisiehSKGJ',
];

/*----- END FILE: env.php -----*/