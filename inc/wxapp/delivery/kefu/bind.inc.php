<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$ta = trim($_GPC['ta']) ? trim($_GPC['ta']) : 'deliveryer';

//加载GatewayClient。安装GatewayClient参见本页面底部介绍
require_once MODULE_ROOT . '/library/GatewayClient/Gateway.php';
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '127.0.0.1:1238';

$_W['kefu']['user'] = $kefu = array(
	'role' => 'deliveryer',
	'uid' => $_W['deliveryer']['id'],
	'token' => $_W['deliveryer']['token'],
	'nickname' => $_W['deliveryer']['nickname'],
	'avatar' => tomedia($_W['deliveryer']['avatar'])
);
if($ta == 'deliveryer') {
	$client_id = $_GPC['client_id'];
	Gateway::bindUid($client_id, $_W['kefu']['user']['token']);
	imessage(error(0, '绑定成功'), '', 'ajax');
}

