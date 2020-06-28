<?php

defined("IN_IA") or exit("Access Denied");
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "basic";
require_once MODULE_ROOT . "/library/GatewayClient/Gateway.php";
GatewayClient\Gateway::$registerAddress = "127.0.0.1:2345";
if ($op == "bind") {
    $_W["kefu"]["user"] = $kefu = array("role" => "kefu", "kefu_id" => $_W["user"]["uid"], "token" => $_W["user"]["token"], "nickname" => $_W["user"]["nickname"], "avatar" => tomedia($_W["user"]["avatar"]), "kefu_status" => $_W["user"]["kefu_status"]);
    $client_id = $_GPC["client_id"];
    GatewayClient\Gateway::bindUid($client_id, $_W["kefu"]["user"]["token"]);
    imessage(error(0, ""), "", "ajax");
} else {
    if ($op == "basic") {
        $_W["page"]["title"] = "基础设置";
        if ($_W["ispost"]) {
            $system = array("status" => intval($_GPC["system"]["status"]), "store_status" => intval($_GPC["system"]["store_status"]), "deliveryer_status" => intval($_GPC["system"]["deliveryer_status"]), "kefu_status" => intval($_GPC["system"]["kefu_status"]), "allotRule" => intval($_GPC["system"]["allotRule"]));
            $autoreply = array("closingTime" => array("status" => intval($_GPC["autoreply"]["closingTime"]["status"]), "content" => trim($_GPC["autoreply"]["closingTime"]["content"])), "busyReply" => array("content" => trim($_GPC["autoreply"]["busyReply"]["content"])));
            if (empty($autoreply["busyReply"]["content"])) {
                imessage(error(-1, "忙碌状态自动回复的内容不能为空"), "", "ajax");
            }
            if ($autoreply["closingTime"]["status"] == 1) {
                if (empty($autoreply["closingTime"]["content"])) {
                    imessage(error(-1, "下班提醒的内容不能为空"), "", "ajax");
                }
                $starttime = array_map("trim", $_GPC["starttime"]);
                $endtime = array_map("trim", $_GPC["endtime"]);
                $worktime = array();
                if (!empty($starttime)) {
                    foreach ($starttime as $key => $value) {
                        if (!empty($value) && !empty($endtime[$key])) {
                            $worktime[] = array("start" => $value, "end" => $endtime[$key]);
                        }
                    }
                }
                if (count($worktime) <= 0) {
                    imessage(error(-1, "请设置工作时间"), "", "ajax");
                }
                $autoreply["worktime"] = $worktime;
                $workday = array(1, 2, 3, 4, 5, 6, 7);
                if (!empty($_GPC["workday"])) {
                    $workday = array_map("intval", $_GPC["workday"]);
                }
                $autoreply["workday"] = $workday;
            }
            $basic = array("system" => $system, "autoreply" => $autoreply);
            set_plugin_config("kefu", $basic);
            imessage(error(0, "客服基础设置设置成功"), "refresh", "ajax");
        }
        $setting = get_plugin_config("kefu");
        include itemplate("config");
    }
}