<?php

defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC['ta']) ? trim($_GPC['ta']) : 'list';
mload()->model('plugin');
pload()->model('gohome');

if ($ta == 'list') {
	$records = gohome_order_fetchall();
	$result = array('records' => $records['orders']);
	imessage(error(0, $result), '', 'ajax');
	return 1;
}

if ($ta == 'cancel') {
	$id = intval($_GPC['id']);
	$team_cancel = intval($_GPC['team_cancel']);
	$extra = array();

	if ($team_cancel == 1) {
		$extra = array('team_cancel' => 1);
	}

	$result = gohome_order_update($id, 'cancel', $extra);
	if (is_array($result['message']) && $result['message']['is_refund']) {
		imessage(error(0, '取消订单成功,' . $result['message']['refund_message']), '', 'ajax');
		return 1;
	}

	imessage(error(0, '取消订单成功'), '', 'ajax');
	return 1;
}

if ($ta == 'detail') {
	$id = intval($_GPC['id']);
	$order = gohome_order_fetch($id);
	$result = array('order' => $order);
	imessage(error(0, $result), '', 'ajax');
	return 1;
}

if ($ta == 'remark') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$remark = trim($_GPC['remark']);
		pdo_update('tiny_wmall_gohome_order', array('remark' => $remark), array('uniacid' => $_W['uniacid'], 'agentid' => $_W['agentid'], 'id' => $id));
		imessage(error(0, '订单成功添加备注'), '', 'ajax');
	}

	$order = gohome_order_fetch($id);
	return 1;
}

if ($ta == 'status') {
	$id = intval($_GPC['id']);
	$type = trim($_GPC['type']);
	$result = gohome_order_update($id, $type);
	imessage($result, '', 'ajax');
	return 1;
}

if ($ta == 'refund_handle') {
	$id = intval($_GPC['id']);
	$refund = gohome_order_begin_refund($id);
	imessage($refund, '', 'ajax');
	return 1;
}

if ($ta == 'refund_status') {
	$id = intval($_GPC['id']);
	$order = gohome_order_fetch($id);

	if (empty($order)) {
		imessage(error(-1, '订单不存在'), '', 'ajax');
	}

	if ($order['refund_status'] == 0) {
		imessage(error(-1, '退款申请不存'), '', 'ajax');
	}

	if ($order['refund_status'] == 3) {
		imessage(error(-1, '已退款成功'), '', 'ajax');
	}

	pdo_update('tiny_wmall_gohome_order', array('refund_status' => 3), array('uniacid' => $_W['uniacid'], 'id' => $order['id']));
	imessage(error(0, '设置为已退款成功'), '', 'ajax');
}

?>
