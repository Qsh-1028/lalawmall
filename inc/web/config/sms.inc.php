<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'set';

if($op == 'set') {
	$_W['page']['title'] = '短信平台';
	if($_W['ispost']) {
		$type = trim($_GPC['type']);
		if(!in_array($type, array('aliyun', 'huawei'))) {
			imessage(error(-1, '请选择短信/语音平台'), '', 'ajax');
		}
		if($type == 'huawei') {
			$sms = array(
				'status' => intval($_GPC['status']),
				'type' => $type,
				'huawei' => array(
					'app_key' => trim($_GPC['app_key']),
					'app_secret' => trim($_GPC['app_secret']),
					'signature' => trim($_GPC['signature']),
					'sender' => trim($_GPC['sender']),
					'app_key_notice' => trim($_GPC['app_key_notice']),
					'app_secret_notice' => trim($_GPC['app_secret_notice']),
					'url' => trim($_GPC['url']),
					'username' => trim($_GPC['username']),
					'password' => trim($_GPC['password']),
				)
			);
		} elseif($type == 'aliyun') {
			$sms = array(
				'status' => intval($_GPC['status']),
				'type' => $type,
				'version' => intval($_GPC['version']),
				'key' => trim($_GPC['key']),
				'secret' => trim($_GPC['secret']),
				'sign' => trim($_GPC['sign']),
			);
		}
		set_system_config('sms.set', $sms);
		imessage(error(0, '短信平台设置成功'), referer(), 'ajax');
	}
	$sms = $_config['sms']['set'];
}

elseif($op == 'template') {
	$_W['page']['title'] = '短信平台';
	if($_W['ispost']) {
		$template = array(
			'verify_code_tpl' => trim($_GPC['verify_code_tpl']),
		);
		set_system_config('sms.template', $template);
		imessage(error(0, '短信模板设置成功'), referer(), 'ajax');
	}
	$sms = $_config['sms']['template'];
}

elseif($op == 'verify') {
	$_W['page']['title'] = '短信验证';
	if($_W['ispost']) {
		$data = array(
			'clerk_register' => intval($_GPC['clerk_register']),
			'consumer_register' => intval($_GPC['consumer_register'])
		);
		set_system_config('sms.verify', $data);
		imessage(error(0, '短信模板设置成功'), referer(), 'ajax');
	}
	$verify = $_config['sms']['verify'];
}

include itemplate('config/sms');


