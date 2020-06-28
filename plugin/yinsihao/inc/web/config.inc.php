<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'basic';

if($op == 'basic') {
	$_W['page']['title'] = '基础设置';
	$errander_status = check_plugin_perm('errander') ? 1 : 0;
	if($_W['ispost']) {
		$yinsihao = $_GPC['yinsihao'];
		$status = intval($yinsihao['status']);
		if(empty($status)) {
			set_plugin_config('yinsihao.basic.status', $status);
			imessage(error(0, '隐私号已关闭'), 'refresh', 'ajax');
		}
		$type = trim($yinsihao['type']);
		if(!in_array($type, array('huawei', 'aliyun'))) {
			imessage(error(-1, '请选择隐私号类型'), '', 'ajax');
		}
		$accessKeyId = trim($yinsihao['accessKeyId']);
		if(empty($accessKeyId)) {
			imessage(error(-1, 'AccessKeyId不能为空'), '', 'ajax');
		}
		$accessSecret = trim($yinsihao['accessSecret']);
		if(empty($accessSecret)) {
			imessage(error(-1, 'AccessSecret不能为空'), '', 'ajax');
		}
		$poolKey = array();
		if($type == 'aliyun') {
			if(!empty($_GPC['poolKey']['poolKey'])) {
				foreach($_GPC['poolKey']['poolKey'] as $key => $value) {
					$value = trim($value);
					$numbers = array_map('trim', explode(',', str_replace('，', ',', $_GPC['poolKey']['numbers'][$key])));
					if(empty($value) || empty($numbers)) {
						continue;
					}
					$poolKey[$value] = $numbers;
				}
			}
			if(empty($poolKey)) {
				imessage(error(-1, '号码池Key不能为空'), '', 'ajax');
			}
		}
		$store_number = array_map('trim', explode(',', str_replace('，', ',', $yinsihao['store_number'])));
		if(empty($store_number)) {
			imessage(error(-1, '商家隐私号段不能为空'), '', 'ajax');
		}
		$deliveryer_number = array_map('trim', explode(',', str_replace('，', ',', $yinsihao['deliveryer_number'])));
		if(empty($deliveryer_number)) {
			imessage(error(-1, '配送员隐私号段不能为空'), '', 'ajax');
		}
		$member_number = array_map('trim', explode(',', str_replace('，', ',', $yinsihao['member_number'])));
		if(empty($member_number)) {
			imessage(error(-1, '外卖顾客隐私号段不能为空'), '', 'ajax');
		}
		$member_expiration = floatval($yinsihao['member_expiration']);
		if($member_expiration < 60) {
			imessage(error(-1, '外卖顾客隐私号有效时长不能低于60分钟'), '', 'ajax');
		}
		if($errander_status == 1) {
			$errander_number = array_map('trim', explode(',', str_replace('，', ',', $yinsihao['errander_number'])));
			if(empty($errander_number)) {
				imessage(error(-1, '跑腿顾客隐私号段不能为空'), '', 'ajax');
			}
			$errander_expiration = floatval($yinsihao['errander_expiration']);
			if($errander_expiration < 60) {
				imessage(error(-1, '跑腿顾客隐私号有效时长不能低于60分钟'), '', 'ajax');
			}
		}
		$basic = array(
			'status' => $status,
			'type' => $type,
			'accessKeyId' => $accessKeyId,
			'accessSecret' => $accessSecret,
			'poolKey' => $poolKey,
			'store_number' => $store_number,
			'member_call_store_status' => intval($yinsihao['member_call_store_status']),
			'member_call_deliveryer_status' => intval($yinsihao['member_call_deliveryer_status']),
			//'store_tpl_status' => intval($yinsihao['store_tpl_status']),
			'deliveryer_number' => $deliveryer_number,
			'deliveryer_tpl_status' => intval($yinsihao['deliveryer_tpl_status']),
			'member_number' => $member_number,
			'member_expiration' => $member_expiration,
			'errander_number' => $errander_number,
			'errander_expiration' => $errander_expiration
		);
		set_plugin_config('yinsihao.basic', $basic);
		$dataProtocol = $_GPC['protocol'];
		set_config_text('号码保护服务协议', "yinsihao:agreement", htmlspecialchars_decode($dataProtocol));
		imessage(error(0, '隐私号基础设置设置成功'), 'refresh', 'ajax');
	}
	$setting = get_plugin_config('yinsihao.basic');
	if(!empty($setting)) {
		if($setting['type'] == 'aliyun' && !empty($setting['poolKey'])) {
			foreach($setting['poolKey'] as $key => $value) {
				$setting['poolKey'][$key] = implode(',', $value);
			}
		}
		$keys = array('store_number', 'deliveryer_number', 'member_number', 'errander_number');
		foreach($keys as $value) {
			if(!empty($setting[$value])) {
				$setting[$value] = implode(',', $setting[$value]);
			}
		}
	}
	$protocol = get_config_text("yinsihao:agreement");
}

include itemplate('config');