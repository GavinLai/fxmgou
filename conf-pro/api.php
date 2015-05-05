<?php
/**
 * Rest API 接口配置
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

return [
  'weixin_fxmgou' => [
    //production setting
    'appId'          => 'wx855212160c85a161',
    'appSecret'      => '101ef890d4befe99695f8824169dbe47',
    'token'          => 'JQ4W443W6D5EFBAT',
    'encodingAesKey' => 'bbeJit1rwF6qbXnhAIxKeO2vsEMTSj6QNbJrtrpITQm',
    'paySignKey'     => '', //公众号支付请求中用于加密的密钥Key
  ]
];
 
/*----- END FILE: api.php -----*/