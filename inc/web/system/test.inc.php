<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
set_time_limit(0);
echo imurl('system/common/vuesession', array('state' => "we7sid-" . $_W["session_id"], "from" => "vue"), true);
exit;