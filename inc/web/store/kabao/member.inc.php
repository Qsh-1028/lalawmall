<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "index";
if ($ta == "index") {
    $_W["page"]["title"] = "会员列表";
    pload()->model('kabao');
    $groups = kabao_get_store_groups($sid);
    $status_group = kabao_vip_status();
    $group_id = intval($_GPC["group_id"]);
    $orderby = empty($_GPC["orderby"]) ? "addtime" : trim($_GPC["orderby"]);
    $filter = array("sid" => $sid);
    $data = kabao_fetchall_vip_member($filter);
    $members = $data["data"];
    $pager = $data["pager"];
}
include itemplate('store/kabao/member');