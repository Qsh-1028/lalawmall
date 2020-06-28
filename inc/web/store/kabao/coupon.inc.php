<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "list";
if ($ta == "list") {
    $_W["page"]["title"] = "门店会员优惠券列表";
    pload()->model('kabao');
    $type = trim($_GPC["type"]);
    $filter = array("sid" => $sid);
    $data = kabao_fetchall_coupon();
    $coupons = $data["data"];
    $pager = $data["pager"];
    $types = kabao_coupon_types();
} else {
    if ($ta == "post") {
        $_W["page"]["title"] = "编辑门店会员优惠券";
        $id = intval($_GPC["id"]);
        $coupon = pdo_get("tiny_wmall_kabao_coupon", array("uniacid" => $_W["uniacid"], "id" => $id));
        if ($_W["ispost"]) {
            $insert = array("sid" => $sid, "type" => trim($_GPC["type"]), "title" => trim($_GPC["title"]), "discount" => intval($_GPC["discount"]), "credit1" => intval($_GPC["credit1"]), "condition" => intval($_GPC["condition"]), "use_days_limit" => intval($_GPC["use_days_limit"]));
            if (empty($insert["title"])) {
                $insert["title"] = "店铺通用满减券";
            }
            if ($insert["discount"] <= 0) {
                imessage(error(-1, "优惠券金额必须是正整数"), "", "ajax");
            }
            if ($insert["condition"] <= 0) {
                imessage(error(-1, "满多少元可用必须是正整数"), "", "ajax");
            }
            if ($insert["condition"] <= $insert["discount"]) {
                imessage(error(-1, "优惠券金额不能大于等于使用条件"), "", "ajax");
            }
            if ($insert["use_days_limit"] <= 0) {
                imessage(error(-1, "领取后几天内有效必须是正整数"), "", "ajax");
            }
            if ($insert["type"] == "exchange" && $insert["credit1"] <= 0) {
                imessage(error(-1, "兑换所需积分必须是正整数"), "", "ajax");
            }
            if ($insert["type"] == "bind") {
                $inserr["credit1"] = 0;
                if (empty($coupon) || $coupon["type"] == "exchange") {
                    $is_exist = pdo_get("tiny_wmall_kabao_coupon", array("uniacid" => $_W["uniacid"], "sid" => $sid, "type" => "bind"));
                    if (!empty($is_exist)) {
                        imessage(error(-1, "绑卡赠券类型的优惠券已存在，无法再次创建"), "", "ajax");
                    }
                }
            }
            if (0 < $id) {
                pdo_update("tiny_wmall_kabao_coupon", $insert, array("uniacid" => $_W["uniacid"], "id" => $id));
            } else {
                $insert["uniacid"] = $_W["uniacid"];
                $insert["addtime"] = TIMESTAMP;
                pdo_insert('tiny_wmall_kabao_coupon', $insert);
            }
            imessage(error(0, '门店会员优惠券设置成功'), iurl('store/kabao/coupon/list'), 'ajax');
        }
    } else {
        if ($ta == "delete") {
            $id = intval($_GPC["id"]);
            pdo_delete('tiny_wmall_kabao_coupon', array('uniacid' => $_W["uniacid"], "id" => $id));
            imessage(error(0, '优惠券删除成功'), iurl('store/kabao/coupon/list'), 'ajax');
        }
    }
}
include itemplate('store/kabao/coupon');