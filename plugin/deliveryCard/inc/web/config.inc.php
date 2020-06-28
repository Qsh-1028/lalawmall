<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "index";
if ($op == "index") {
    $_W["page"]["title"] = "基础设置";
    if ($_W["ispost"]) {
        $deliveryCard = array("card_apply_status" => intval($_GPC["card_apply_status"]));
        $diypower = array();
        $ids = array_map("trim", $_GPC["id"]);
        if (!empty($ids)) {
            $titles = array_map("trim", $_GPC["title"]);
            $imgs = array_map("trim", $_GPC["img"]);
            $urls = array_map("trim", $_GPC["url"]);
            foreach ($ids as $key => $value) {
                if (!empty($titles[$key]) && !empty($imgs[$key])) {
                    $diypower[] = array("id" => $value, "title" => $titles[$key], "img" => $imgs[$key], "url" => !empty($urls[$key]) ? $urls[$key] : "package/pages/deliveryCard/power");
                }
            }
        }
        if (!empty($diypower)) {
            $deliveryCard["diypower"] = $diypower;
        }
        $tips = array_map("trim", $_GPC["tips"]);
        $diytips = array();
        if (!empty($tips)) {
            foreach ($tips as $key => $value) {
                if (!empty($value)) {
                    $diytips[] = array("text" => $value);
                }
            }
        }
        if (!empty($diytips)) {
            $deliveryCard["diytips"] = $diytips;
        }
        set_plugin_config('deliveryCard', $deliveryCard);
        set_config_text('配送会员卡规则', 'agreement_card', htmlspecialchars_decode($_GPC["agreement_card"]));
        imessage(error(0, '设置配送会员卡参数成功'), 'refresh', 'ajax');
    }
    $agreement_card = get_config_text("agreement_card");
}
include itemplate('config');