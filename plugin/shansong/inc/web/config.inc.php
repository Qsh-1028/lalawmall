<?php

defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';

if ($op == 'index') {
	$_W['page']['title'] = '基础设置';

	if ($_W['ispost']) {
		$type = trim($_GPC['type']);
		if ($type != 'store' && $type != 'plateform') {
			imessage(error(-1, '请选择对接模式'), '', 'ajax');
		}

		$data = array('status' => intval($_GPC['status']), 'type' => $type, 'city' => trim($_GPC['city']), 'mobile' => trim($_GPC['mobile']), 'md5' => trim($_GPC['md5']), 'token' => trim($_GPC['shansongtoken']), 'merchantid' => trim($_GPC['merchantid']), 'partnerNO' => trim($_GPC['partnerNO']));
		set_plugin_config('shansong', $data);
		imessage(error(0, '设置成功'), 'refresh', 'ajax');
	}

	$notify_url = WE7_WMALL_URL . 'plugin/shansong/notify.php';
	$shansong = get_plugin_config('shansong');
}

include itemplate('config');

?>
