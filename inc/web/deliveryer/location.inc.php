<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
mload()->model('deliveryer');
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
if ($op == "list") {
    $_W["page"]["title"] = "配送员位置";
    $condition = " WHERE a.uniacid = :uniacid";
    $params = array(":uniacid" => $_W["uniacid"]);
    $deliveryer_id = intval($_GPC["deliveryer_id"]);
    if (0 < $deliveryer_id) {
        $condition .= " AND a.deliveryer_id = :deliveryer_id";
        $params[":deliveryer_id"] = $deliveryer_id;
    }
    if (!empty($_GPC["addtime"])) {
        $starttime = strtotime($_GPC["addtime"]["start"]);
        $endtime = strtotime($_GPC["addtime"]["end"]) + 86399;
    } else {
        $starttime = strtotime("-31 day");
        $endtime = TIMESTAMP;
    }
    $condition .= " AND a.addtime > :start AND a.addtime < :end";
    $params[":start"] = $starttime;
    $params[":end"] = $endtime;
    $pindex = max(1, intval($_GPC["page"]));
    $psize = 100;
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_deliveryer_location_log") . " as a left join " . tablename("tiny_wmall_deliveryer") . " as b on a.deliveryer_id = b.id" . $condition, $params);
    $records = pdo_fetchall("SELECT a.*, b.id as delivery_id, b.title, b.avatar FROM " . tablename("tiny_wmall_deliveryer_location_log") . " as a left join " . tablename("tiny_wmall_deliveryer") . " as b on a.deliveryer_id = b.id" . $condition . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
    $deliveryers = deliveryer_all(true);
} else {
    if ($op == "del") {
        $ids = $_GPC["id"];
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        foreach ($ids as $id) {
            pdo_delete("tiny_wmall_deliveryer_location_log", array("uniacid" => $_W["uniacid"], "id" => $id));
        }
        imessage(error(0, '删除错误日志成功'), referer(), 'ajax');
    } else {
        if ($op == "delAll") {
            if ($_W["ispost"]) {
                pdo_delete("tiny_wmall_deliveryer_location_log", array("uniacid" => $_W["uniacid"]));
            }
            imessage(error(0, '删除错误日志成功'), referer(), 'ajax');
        }
    }
}
include itemplate('deliveryer/location');