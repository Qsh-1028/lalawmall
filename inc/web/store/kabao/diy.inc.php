<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$ta = trim($_GPC['ta']) ? trim($_GPC['ta']) : 'index';

if($ta == 'index') {
	$_W['page']['title'] = '自定义会员卡详情页';
	mload()->model('plugin');
	pload()->model('kabao');
	$page = kabao_get_page($sid, false);
	if($_W['ispost']) {
		$insert = array(
			'uniacid' => $_W['uniacid'],
			'sid' => $sid,
			'type' => 'kabao',
			'data' => base64_encode(json_encode($_GPC['data'])),
			'addtime' => TIMESTAMP,
		);
		if($page['sid'] == $sid) {
			pdo_update('tiny_wmall_store_page', $insert, array('uniacid' => $_W['uniacid'], 'sid' => $sid, 'type' => 'kabao'));
		} else {
			pdo_insert('tiny_wmall_store_page', $insert);
		}
		imessage(error(0, '门店会员卡设置成功'), iurl('store/kabao/diy'), 'ajax');
	}
	$store_id = $sid;
	$store = store_fetch($sid, array('id', 'title', 'logo'));
	$plugins = get_available_plugin();
}

include itemplate('store/kabao/diy');

