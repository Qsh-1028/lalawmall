<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$_W["page"]["title"] = "配送统计";
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "store";
if ($ta == "store") {
    $sids = $_W["deliveryer"]["sids"];
    if (empty($sids)) {
        imessage(error(0, "您不是店内配送员"), imurl("delivery/member/mine", array()), "error");
    }
    $condition = " where uniacid = :uniacid and status = 5 and delivery_type = 1 and order_type <= 2 and deliveryer_id = :deliveryer_id and stat_day = :stat_day";
    $params = array(":uniacid" => $_W["uniacid"], ":deliveryer_id" => $_W["deliveryer"]["id"], "stat_day" => date("Ymd"));
    $condition .= " and sid in (" . $_W["deliveryer"]["sids_sn"] . ")";
    $orders = pdo_fetchall("select sid, count(*) as order_num, sum(num) as goods_num from " . tablename("tiny_wmall_order") . " " . $condition . " group by sid", $params, "sid");
    $stores = pdo_fetchall("select id, title from " . tablename("tiny_wmall_store") . " where uniacid = :uniacid and id in (" . $_W["deliveryer"]["sids_sn"] . ")", array(":uniacid" => $_W["uniacid"]), "id");
    $records = array();
    if (!empty($stores)) {
        foreach ($stores as $store) {
            if (array_key_exists($store["id"], $orders)) {
                $records[] = array("id" => $store["id"], "title" => $store["title"], "order_num" => intval($orders[$store["id"]]["order_num"]), "goods_num" => intval($orders[$store["id"]]["goods_num"]));
            } else {
                $records[] = array("id" => $store["id"], "title" => $store["title"], "order_num" => 0, "goods_num" => 0);
            }
        }
    }
}
include itemplate('member/statcenter');