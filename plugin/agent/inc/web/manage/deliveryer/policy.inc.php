<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "index";
if ($op == "index") {
    $_W["page"]["title"] = "配送员配送策略";
    if ($_W["ispost"]) {
        $form_type = trim($_GPC["form_type"]);
        if ($form_type == "deliveryer") {
            $special_deliveryer = array("fee_takeout" => array("status" => intval($_GPC["fee_takeout"]["status"]), "type" => intval($_GPC["fee_takeout"]["type"]), "fee" => floatval($_GPC["fee_takeout"]["fee"]), "rate" => floatval($_GPC["fee_takeout"]["rate"])));
            if (check_plugin_perm('errander')) {
                $special_deliveryer["fee_errander"] = array("status" => intval($_GPC["fee_errander"]["status"]), "type" => intval($_GPC["fee_errander"]["type"]), "fee" => floatval($_GPC["fee_errander"]["fee"]), "rate" => floatval($_GPC["fee_errander"]["rate"]));
            }
            set_agent_system_config('takeout.special.deliveryer', $special_deliveryer);
            imessage(error(0, '一键增加配送员提成设置成功'), referer(), 'ajax');
        }
    }
    $special = get_agent_system_config("takeout.special");
    include itemplate('deliveryer/policy');
}