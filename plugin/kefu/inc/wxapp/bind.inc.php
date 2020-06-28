<?php

defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'a';
//加载GatewayClient。安装GatewayClient参见本页面底部介绍
require_once MODULE_ROOT . '/library/GatewayClient/Gateway.php';
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '127.0.0.1:1238';
icheckauth();
$fans = array('role' => 'member', 'token' => $_W['member']['token'], 'nickname' => $_W['member']['nickname'], 'avatar' => tomedia($_W['member']['avatar']));
if ($op == 'member') {
    icheckauth();
    $fans = array('role' => 'member', 'token' => $_W['member']['token'], 'nickname' => $_W['member']['nickname'], 'avatar' => tomedia($_W['member']['avatar']));
    $client_id = $_GPC['client_id'];
    Gateway::bindUid($client_id, $fans['token']);
    /*$result = array(
          'type' => 'message',
          'data' => array(
              'content' => 'dddd',
              'id' => 'lll',
              'type' => 'kefu'
          )
      );
      Gateway::sendToClient($client_id, json_encode($result));*/
    imessage(error(0, ''), '', 'ajax');
}