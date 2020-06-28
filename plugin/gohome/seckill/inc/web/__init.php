<?php

function seckill_all_times()
{
	$data = array();
	$i = 0;

	while ($i < 24) {
		$data[] = $i;
		++$i;
	}

	return $data;
}

defined('IN_IA') || exit('Access Denied');
global $_W;
global $_GPC;

?>
