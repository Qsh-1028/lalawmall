<?php

defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC['ta']) ? trim($_GPC['ta']) : 'link';

if ($ta == 'link') {
	mload()->model('plateform');
	$type = empty($_GPC['type']) ? 'plateform' : trim($_GPC['type']);
	$urls = wxapp_urls($type);
	include itemplate('public/plateformLink');
}

?>
