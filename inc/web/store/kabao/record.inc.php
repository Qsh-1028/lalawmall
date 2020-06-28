<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "index";
if ($ta == "index") {
    $_W["page"]["title"] = "会员积分明细";
    pload()->model('kabao');
    $trade_type = trim($_GPC["trade_type"]);
    $filter = array("sid" => $sid);
    $data = kabao_fetchall_credit_record($filter);
    $records = $data["data"];
    $pager = $data["pager"];
    $trade_types = kabao_credit_record_trade_types();
}
include itemplate('store/kabao/record');