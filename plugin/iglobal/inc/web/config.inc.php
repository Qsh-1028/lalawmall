<?php
defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;
$_W['page']['title'] = '基础设置';
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';

if ($op == 'index') {
	if ($_W['ispost']) {
		$dollar = trim($_GPC['dollar']);

		if (empty($dollar)) {
			imessage(error(-1, '请选择语言'), '', 'ajax');
		}

		$map_type = trim($_GPC['map_type']);

		if (empty($map_type)) {
			imessage(error(-1, '请选择地图类型'), '', 'ajax');
		}

		$dollarrate = floatval($_GPC['dollarrate']);

		if (empty($dollarrate)) {
			imessage(error(-1, '兑换汇率不能为0'), '', 'ajax');
		}

		$data = array('dollar' => $dollar, 'map_type' => $map_type, 'dollarrate' => $dollarrate, 'lang' => trim($_GPC['lang']));
		set_system_config('iglobal', $data);
		imessage(error(0, '参数保存成功'), iurl('iglobal/config/index'), 'ajax');
	}

	$config = $_W['we7_wmall']['config']['iglobal'];
}

include itemplate('config');

?>