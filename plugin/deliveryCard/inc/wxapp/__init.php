<?php

defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;

if ($_config_plugin['card_apply_status'] != 1) {
	imessage(error(-1, '平台未开启配送会员卡功能'), '', 'ajax');
}

?>
