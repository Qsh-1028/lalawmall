<?php

defined('IN_IA') or exit('Access Denied');
function order_count_activity($sid, $cart, $recordid = 0, $redPacket_id = 0, $delivery_price = 0, $delivery_free_price = 0, $order_type = "")
{
    global $_W;
    global $_GPC;
    $activityed = array("list" => "", "total" => 0, "activity" => 0, "token" => 0, "store_discount_fee" => 0, "agent_discount_fee" => 0, "plateform_discount_fee" => 0);
    $store = store_fetch($sid, array("delivery_mode", "delivery_fee_mode", "delivery_extra", "delivery_free_price", "cid"));
    $order_type = intval($order_type);
    if ($order_type <= 2) {
        $cart["price"] = $cart["price"] + $cart["box_price"];
    }
    if (($order_type == 1 || empty($order_type)) && ($_GPC["ac"] == "order" && $_GPC["op"] == "create" || defined("IN_WXAPP"))) {
        if (isset($delivery_free_price)) {
            $store["delivery_free_price"] = $delivery_free_price;
        }
        if (!empty($delivery_price) && $store["delivery_fee_mode"] <= 3 && 0 < $store["delivery_free_price"] && $store["delivery_free_price"] <= $cart["price"]) {
            if ($store["delivery_mode"] == 1) {
                $store_discount_fee = $delivery_price;
                $agent_discount_fee = 0;
                $plateform_discount_fee = 0;
            } else {
                $delivery_free_bear = trim($store["delivery_extra"]["delivery_free_bear"]);
                if ($_W["is_agent"]) {
                    $agent_discount_fee = $delivery_price;
                    $plateform_discount_fee = 0;
                    $store_discount_fee = 0;
                    if ($delivery_free_bear == "store") {
                        $agent_discount_fee = 0;
                        $store_discount_fee = $delivery_price;
                    }
                } else {
                    $agent_discount_fee = 0;
                    $plateform_discount_fee = $delivery_price;
                    $store_discount_fee = 0;
                    if ($delivery_free_bear == "store") {
                        $plateform_discount_fee = 0;
                        $store_discount_fee = $delivery_price;
                    }
                }
            }
            $activityed["list"]["delivery"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $delivery_price, "value" => $delivery_price, "type" => "delivery", "name" => "满" . $store["delivery_free_price"] . $_W["Lang"]["dollarSignCn"] . "免配送费", "icon" => "mian_b.png", "plateform_discount_fee" => $plateform_discount_fee, "store_discount_fee" => $store_discount_fee, "agent_discount_fee" => $agent_discount_fee);
            $activityed["total"] += $delivery_price;
            $activityed["activity"] += $delivery_price;
            $activityed["store_discount_fee"] += $store_discount_fee;
            $activityed["plateform_discount_fee"] += $plateform_discount_fee;
            $activityed["agent_discount_fee"] += $agent_discount_fee;
        }
        if (empty($activityed["list"]["delivery"]) && $store["delivery_mode"] == 2 && !empty($delivery_price) && 0 < $_W["member"]["setmeal_id"] && TIMESTAMP <= $_W["member"]["setmeal_endtime"]) {
            $nums = pdo_fetchcolumn("select count(*) from " . tablename("tiny_wmall_order") . " where uniacid = :uniacid and uid = :uid and vip_free_delivery_fee = 1 and status != 6 and addtime >= :addtime", array(":uniacid" => $_W["uniacid"], ":uid" => $_W["member"]["uid"], ":addtime" => strtotime(date("Y-m-d"))));
            if ($nums < $_W["member"]["setmeal_day_free_limit"] && 0 < $_W["member"]["setmeal_day_free_limit"] || empty($_W["member"]["setmeal_day_free_limit"])) {
                $free_delivery_price = $delivery_price;
                if ($_W["member"]["setmeal_deliveryfee_free_limit"] < $delivery_price && 0 < $_W["member"]["setmeal_deliveryfee_free_limit"]) {
                    $free_delivery_price = $_W["member"]["setmeal_deliveryfee_free_limit"];
                }
                $activityed["list"]["vip_delivery"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $free_delivery_price, "value" => $free_delivery_price, "type" => "delivery", "name" => "会员免配送费", "icon" => "mian_b.png", "plateform_discount_fee" => $free_delivery_price, "agent_discount_fee" => 0, "store_discount_fee" => 0);
                $activityed["total"] += $free_delivery_price;
                $activityed["activity"] += $free_delivery_price;
                $activityed["store_discount_fee"] += 0;
                $activityed["plateform_discount_fee"] += $free_delivery_price;
                $activityed["agent_discount_fee"] += 0;
            }
        }
    }
    if ($cart["bargain_use_limit"] != 2) {
        mload()->model("activity");
        $activity = activity_getall($sid, 1);
        if (($order_type == 2 || empty($order_type)) && !empty($activity) && !empty($activity["selfPickup"])) {
            $activity_selfPickup = $activity["selfPickup"];
            if ($activity_selfPickup["status"] == 1) {
                $discount_temp = array_compare($cart["price"], $activity_selfPickup["data"]);
                if (!empty($discount_temp)) {
                    $discount = array("back" => $discount_temp["back"], "plateform_discount_fee" => $discount_temp["plateform_charge"], "store_discount_fee" => $discount_temp["store_charge"], "agent_discount_fee" => $discount_temp["agent_charge"]);
                    $activityed["list"]["selfPickup"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $discount["back"], "value" => $discount["back"], "type" => "discount", "name" => "自提满减优惠", "icon" => "discount_b.png", "store_discount_fee" => $discount["store_discount_fee"], "agent_discount_fee" => $discount["agent_discount_fee"], "plateform_discount_fee" => $discount["plateform_discount_fee"]);
                    $activityed["total"] += $discount["back"];
                    $activityed["activity"] += $discount["back"];
                    $activityed["store_discount_fee"] += $discount["store_discount_fee"];
                    $activityed["plateform_discount_fee"] += $discount["plateform_discount_fee"];
                    $activityed["agent_discount_fee"] += $discount["agent_discount_fee"];
                }
            }
        }
        if (!empty($activity) && ($order_type == 2 || empty($order_type)) && empty($activityed["list"]["selfPickup"])) {
            $selfDelivery = $activity["selfDelivery"];
            if (!empty($selfDelivery["status"])) {
                $discount_temp = array_compare($cart["price"], $selfDelivery["data"]);
                if (!empty($discount_temp)) {
                    $discount_fee = round((10 - $discount_temp["back"]) / 10 * $cart["price"], 2);
                    $discount = array("back" => $discount_temp["back"], "value" => $discount_fee, "plateform_discount_fee" => round($discount_fee * $discount_temp["plateform_charge"] / $discount_temp["back"], 2), "agent_discount_fee" => round($discount_fee * $discount_temp["agent_charge"] / $discount_temp["back"], 2), "store_discount_fee" => round($discount_fee * $discount_temp["store_charge"] / $discount_temp["back"], 2));
                    $activityed["list"]["selfDelivery"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $discount["value"], "value" => $discount["value"], "type" => "selfDelivery", "name" => "自提优惠", "icon" => "selfDelivery_b.png", "store_discount_fee" => $discount["store_discount_fee"], "agent_discount_fee" => $discount["agent_discount_fee"], "plateform_discount_fee" => $discount["plateform_discount_fee"]);
                    $activityed["total"] += $discount["value"];
                    $activityed["activity"] += $discount["value"];
                    $activityed["store_discount_fee"] += $discount["store_discount_fee"];
                    $activityed["agent_discount_fee"] += $discount["agent_discount_fee"];
                    $activityed["plateform_discount_fee"] += $discount["plateform_discount_fee"];
                    if ($order_type == 2) {
                        return $activityed;
                    }
                }
            }
        }
    }
    if (0 < $redPacket_id || is_array($redPacket_id)) {
        mload()->model("redPacket");
        $record = redpacket_available_check($redPacket_id, $cart["price"], explode("|", $store["cid"]), array("scene" => "waimai", "sid" => $sid, "order_type" => $order_type));
        if (!is_error($record) && ($record["type"] != "mallNewMember" || $record["type"] == "mallNewMember" && $_W["member"]["is_mall_newmember"])) {
            $activityed["list"]["redPacket"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $record["discount"], "value" => $record["discount"], "type" => "redPacket", "name" => "平台红包优惠", "icon" => "redPacket_b.png", "redPacket_id" => $record["id"], "plateform_discount_fee" => $record["data"]["discount_bear"]["plateform_charge"], "agent_discount_fee" => $record["data"]["discount_bear"]["agent_charge"], "store_discount_fee" => $record["data"]["discount_bear"]["store_charge"]);
            $activityed["redPacket"] = $record;
            $activityed["total"] += $record["discount"];
            $activityed["activity"] += $record["discount"];
            $activityed["store_discount_fee"] += $record["data"]["discount_bear"]["store_charge"];
            $activityed["agent_discount_fee"] += $record["data"]["discount_bear"]["agent_charge"];
            $activityed["plateform_discount_fee"] += $record["data"]["discount_bear"]["plateform_charge"];
            if ($record["type"] == "mallNewMember") {
                return $activityed;
            }
        }
    }
    if ($cart["bargain_use_limit"] == 2) {
        return $activityed;
    }
    if (0 < $recordid) {
        $record = pdo_get("tiny_wmall_activity_coupon_record", array("uniacid" => $_W["uniacid"], "sid" => $sid, "uid" => $_W["member"]["uid"], "status" => 1, "id" => $recordid));
        if (!empty($record) && $record["starttime"] <= TIMESTAMP && TIMESTAMP <= $record["endtime"] && $record["condition"] <= $cart["price"]) {
            $activityed["list"]["token"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $record["discount"], "value" => $record["discount"], "type" => "couponCollect", "name" => "代金券优惠", "icon" => "couponCollect_b.png", "recordid" => $recordid, "plateform_discount_fee" => 0, "agent_discount_fee" => 0, "store_discount_fee" => $record["discount"]);
            $activityed["total"] += $record["discount"];
            $activityed["activity"] += $record["discount"];
            $activityed["store_discount_fee"] += $record["discount"];
            $activityed["agent_discount_fee"] += 0;
            $activityed["plateform_discount_fee"] += 0;
        }
    }
    if (!empty($activity)) {
        $mallNewMember = $activity["mallNewMember"];
        if (!empty($mallNewMember["status"]) && !empty($_W["member"]["is_mall_newmember"])) {
            $discount = array("back" => $mallNewMember["data"]["back"], "plateform_discount_fee" => $mallNewMember["data"]["plateform_charge"], "store_discount_fee" => floatval($mallNewMember["data"]["store_charge"]), "agent_discount_fee" => $mallNewMember["data"]["agent_charge"]);
            $activityed["list"]["mallNewMember"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $discount["back"], "value" => $discount["back"], "type" => "mallNewMember", "name" => "首单优惠", "icon" => "mallNewMember_b.png", "store_discount_fee" => $discount["store_discount_fee"], "plateform_discount_fee" => $discount["plateform_discount_fee"], "agent_discount_fee" => $discount["agent_discount_fee"]);
            $activityed["total"] += $discount["back"];
            $activityed["activity"] += $discount["back"];
            $activityed["store_discount_fee"] += $discount["store_discount_fee"];
            $activityed["agent_discount_fee"] += $discount["agent_discount_fee"];
            $activityed["plateform_discount_fee"] += $discount["plateform_discount_fee"];
        }
        if (!empty($activity["newMember"])) {
            $newMember = $activity["newMember"];
            if ($newMember["status"] == 1 && !empty($_W["member"]["is_store_newmember"])) {
                $discount = array("back" => $newMember["data"]["back"], "plateform_discount_fee" => $newMember["data"]["plateform_charge"], "store_discount_fee" => $newMember["data"]["store_charge"], "agent_discount_fee" => $newMember["data"]["agent_charge"]);
                $activityed["list"]["newMember"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $discount["back"], "value" => $discount["back"], "type" => "newMember", "name" => "新用户优惠", "icon" => "newMember_b.png", "store_discount_fee" => $discount["store_discount_fee"], "agent_discount_fee" => $discount["agent_discount_fee"], "plateform_discount_fee" => $discount["plateform_discount_fee"]);
                $activityed["total"] += $discount["back"];
                $activityed["activity"] += $discount["back"];
                $activityed["store_discount_fee"] += $discount["store_discount_fee"];
                $activityed["plateform_discount_fee"] += $discount["plateform_discount_fee"];
                $activityed["agent_discount_fee"] += $discount["agent_discount_fee"];
            }
        }
        if (empty($activityed["list"]["mallNewMember"]) && !empty($activity["discount"])) {
            $activity_discount = $activity["discount"];
            if ($activity_discount["status"] == 1) {
                $discount_temp = array_compare($cart["price"], $activity_discount["data"]);
                if (!empty($discount_temp)) {
                    $discount = array("back" => $discount_temp["back"], "plateform_discount_fee" => $discount_temp["plateform_charge"], "store_discount_fee" => $discount_temp["store_charge"], "agent_discount_fee" => $discount_temp["agent_charge"]);
                    $activityed["list"]["discount"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $discount["back"], "value" => $discount["back"], "type" => "discount", "name" => "满减优惠", "icon" => "discount_b.png", "store_discount_fee" => $discount["store_discount_fee"], "agent_discount_fee" => $discount["agent_discount_fee"], "plateform_discount_fee" => $discount["plateform_discount_fee"]);
                    $activityed["total"] += $discount["back"];
                    $activityed["activity"] += $discount["back"];
                    $activityed["store_discount_fee"] += $discount["store_discount_fee"];
                    $activityed["plateform_discount_fee"] += $discount["plateform_discount_fee"];
                    $activityed["agent_discount_fee"] += $discount["agent_discount_fee"];
                }
            }
        }
        $cashGrant = $activity["cashGrant"];
        if (!empty($cashGrant["status"])) {
            $discount = array_compare($cart["price"], $cashGrant["data"]);
            if (!empty($discount)) {
                $activityed["list"]["cashGrant"] = array("text" => "返" . $discount["back"] . $_W["Lang"]["dollarSignCn"], "value" => $discount["back"], "type" => "cashGrant", "name" => "返现优惠", "icon" => "cashGrant_b.png", "store_discount_fee" => $discount["store_charge"], "agent_discount_fee" => $discount["agent_charge"], "plateform_discount_fee" => $discount["plateform_charge"]);
                $activityed["total"] += 0;
                $activityed["activity"] += 0;
                $activityed["store_discount_fee"] += $discount["store_charge"];
                $activityed["plateform_discount_fee"] += $discount["plateform_charge"];
                $activityed["agent_discount_fee"] += $discount["agent_charge"];
            }
        }
        $grant = $activity["grant"];
        if (!empty($grant["status"])) {
            $discount = array_compare($cart["price"], $grant["data"]);
            if (!empty($discount)) {
                $activityed["list"]["grant"] = array("text" => (string) $discount["back"], "value" => 0, "type" => "grant", "name" => "满赠优惠", "icon" => "grant_b.png");
                $activityed["total"] += 0;
                $activityed["activity"] += 0;
            }
        }
        $coupon_grant = $activity["couponGrant"];
        if (!empty($coupon_grant["status"])) {
            mload()->model("coupon");
            $coupon = coupon_grant_available($sid, $cart["price"]);
            if (!empty($coupon)) {
                $activityed["list"]["couponGrant"] = array("text" => "返" . $coupon["discount"] . $_W["Lang"]["dollarSignCn"] . "代金券", "value" => 0, "type" => "couponGrant", "name" => "满返优惠", "icon" => "couponGrant_b.png");
                $activityed["total"] += 0;
                $activityed["activity"] += 0;
            }
        }
        $deliveryFeeDiscount = $activity["deliveryFeeDiscount"];
        if (!empty($_W["member"]["kabao"]) && $_W["member"]["kabao"]["free_delivery_fee"] == 1) {
            $freeDeliveryFee = store_get_data($sid, "kabao.deliveryFee");
            if (!empty($freeDeliveryFee)) {
                if (!empty($deliveryFeeDiscount["data"]) && $deliveryFeeDiscount["status"] == 1) {
                    foreach ($freeDeliveryFee as $key => $value) {
                        if (in_array($key, array_keys($deliveryFeeDiscount["data"]))) {
                            if ($deliveryFeeDiscount["data"][$key]["back"] < $value["back"]) {
                                $deliveryFeeDiscount["data"][$key] = $value;
                            }
                        } else {
                            $deliveryFeeDiscount["data"][$key] = $value;
                        }
                    }
                } else {
                    $deliveryFeeDiscount["status"] = 1;
                    $deliveryFeeDiscount["data"] = $freeDeliveryFee;
                }
            }
        }
        if (empty($activityed["list"]["delivery"]) && empty($activityed["list"]["vip_delivery"]) && ($order_type == 1 || empty($order_type)) && ($_GPC["ac"] == "order" && $_GPC["op"] == "create" || defined("IN_WXAPP")) && !empty($deliveryFeeDiscount) && !empty($deliveryFeeDiscount["status"])) {
            $deliveryFeeDiscount_temp = array_compare($cart["price"], $deliveryFeeDiscount["data"]);
            if (!empty($deliveryFeeDiscount_temp)) {
                $final_deliveryFeeDiscount = min($deliveryFeeDiscount_temp["back"], $delivery_price);
                if ($store["delivery_mode"] == 1) {
                    $store_delivery_discount_fee = $final_deliveryFeeDiscount;
                    $agent_delivery_discount_fee = 0;
                    $plateform_delivery_discount_fee = 0;
                } else {
                    if ($final_deliveryFeeDiscount == $deliveryFeeDiscount_temp["back"]) {
                        $store_delivery_discount_fee = $deliveryFeeDiscount_temp["store_charge"];
                        $agent_delivery_discount_fee = $deliveryFeeDiscount_temp["agent_charge"];
                        $plateform_delivery_discount_fee = $deliveryFeeDiscount_temp["plateform_charge"];
                    } else {
                        $store_bear_rate = round($deliveryFeeDiscount_temp["store_charge"] / $deliveryFeeDiscount_temp["back"], 2);
                        $agent_bear_rate = round($deliveryFeeDiscount_temp["agent_charge"] / $deliveryFeeDiscount_temp["back"], 2);
                        $plateform_bear_rate = round($deliveryFeeDiscount_temp["plateform_charge"] / $deliveryFeeDiscount_temp["back"], 2);
                        $store_delivery_discount_fee = round($final_deliveryFeeDiscount * $store_bear_rate, 2);
                        $agent_delivery_discount_fee = round($final_deliveryFeeDiscount * $agent_bear_rate, 2);
                        $plateform_delivery_discount_fee = round($final_deliveryFeeDiscount * $plateform_bear_rate, 2);
                    }
                }
                $activityed["list"]["deliveryFeeDiscount"] = array("text" => "-" . $_W["Lang"]["dollarSign"] . $final_deliveryFeeDiscount, "value" => $final_deliveryFeeDiscount, "type" => "delivery", "name" => "满" . $deliveryFeeDiscount_temp["condition"] . $_W["Lang"]["dollarSignCn"] . "减" . $deliveryFeeDiscount_temp["back"] . $_W["Lang"]["dollarSignCn"] . "配送费", "icon" => "discount_b.png", "plateform_discount_fee" => $plateform_delivery_discount_fee, "store_discount_fee" => $store_delivery_discount_fee, "agent_discount_fee" => $agent_delivery_discount_fee);
                $activityed["total"] += $final_deliveryFeeDiscount;
                $activityed["activity"] += $final_deliveryFeeDiscount;
                $activityed["store_discount_fee"] += $store_delivery_discount_fee;
                $activityed["plateform_discount_fee"] += $plateform_delivery_discount_fee;
                $activityed["agent_discount_fee"] += $agent_delivery_discount_fee;
            }
        }
    }
    return $activityed;
}
function order_status()
{
    $data = array(array("css" => "", "text" => "所有", "color" => ""), array("css" => "label label-default", "text" => "待确认", "color" => ""), array("css" => "label label-info", "text" => "处理中", "color" => "color-primary"), array("css" => "label label-warning", "text" => "待配送", "color" => "color-warning"), array("css" => "label label-warning", "text" => "配送中", "color" => "color-warning"), array("css" => "label label-success", "text" => "已完成", "color" => "color-success"), array("css" => "label label-danger", "text" => "已取消", "color" => "color-danger"));
    return $data;
}
function order_insert_status_log($id, $type, $note = "", $role = "", $role_cn = "")
{
    global $_W;
    if (empty($type)) {
        return false;
    }
    if (in_array($type, array("place_order", "pay"))) {
        $order = order_fetch($id);
    }
    $config_takeout = $_W["we7_wmall"]["config"]["takeout"]["order"];
    $notes = array("place_order" => array("status" => 1, "title" => "订单提交成功", "note" => "单号:" . $order["ordersn"], "ext" => array(array("key" => "pay_time_limit", "title" => "订单待支付", "note" => "请在订单提交后" . $config_takeout["pay_time_limit"] . "分钟内完成支付"))), "handle" => array("status" => 2, "title" => "商户已确认订单", "note" => "正在为您准备商品"), "delivery_wait" => array("status" => 3, "title" => "商品已准备就绪", "note" => "商品已准备就绪,正在分配配送员"), "delivery_ing" => array("status" => 3, "title" => "商品已准备就绪", "note" => "商品已准备就绪,商家正在为您配送中"), "delivery_assign" => array("status" => 4, "title" => "已分配配送员正为您取货中", "note" => ""), "delivery_instore" => array("status" => 12, "title" => "配送员已到店", "note" => "配送员已到店, 正等待商家为您出餐"), "delivery_takegoods" => array("status" => 12, "title" => "配送员已取货", "note" => "商家已出单, 骑士将骑上战马为您急速送达"), "delivery_transfer" => array("status" => 13, "title" => "配送员申请转单", "note" => ""), "end" => array("status" => 5, "title" => "订单已完成", "note" => "任何意见和吐槽,都欢迎联系我们"), "cancel" => array("status" => 6, "title" => "订单已取消", "note" => ""), "pay" => array("status" => 7, "title" => "订单已支付", "note" => "支付成功.付款时间:" . date("Y-m-d H:i:s"), "ext" => array(array("key" => "handle_time_limit", "title" => "等待商户接单", "note" => (string) $config_takeout["handle_time_limit"] . "分钟内商户未接单,将自动取消订单"))), "remind" => array("status" => 8, "title" => "商家已收到催单", "note" => "商家会尽快回复您的催单请求"), "remind_reply" => array("status" => 9, "title" => "商家回复了您的催单", "note" => ""), "delivery_success" => array("status" => 10, "title" => "订单配送完成", "note" => ""), "delivery_fail" => array("status" => 10, "title" => "订单配送失败", "note" => ""), "pay_notice" => array("status" => 1, "title" => "您的订单未支付，请尽快支付"), "direct_transfer" => array("status" => 1, "title" => "配送员发起定向转单申请"), "direct_transfer_agree" => array("status" => 1, "title" => "配送员同意接受转单"), "direct_transfer_refuse" => array("status" => 1, "title" => "配送员拒绝接受转单"), "third_party_cancel_order" => array("status" => 1, "title" => "第三方配送平台取消订单"));
    if ($type == "pay" && $order["order_type"] == 3 && $order["pay_type"] == "finishMeal") {
        $notes["pay"] = array("status" => 7, "title" => "订单支付", "note" => "您选择餐后付款，请在用餐完成后到门店收银台完成支付。");
    }
    $title = $notes[$type]["title"];
    $note = $note ? $note : $notes[$type]["note"];
    $role = !empty($role) ? $role : $_W["role"];
    $role_cn = !empty($role_cn) ? $role_cn : $_W["role_cn"];
    $hash = random(20);
    if ($type == "delivery_assign") {
        $hash = md5((string) $id . $type . TIMESTAMP);
    }
    $data = array("uniacid" => $_W["uniacid"], "oid" => $id, "status" => $notes[$type]["status"], "type" => $type, "role" => $role, "role_cn" => $role_cn, "title" => $title, "note" => $note, "addtime" => TIMESTAMP, "hash" => $hash);
    pdo_insert('tiny_wmall_order_status_log', $data);
    $insert_id = pdo_insertid();
    if (empty($insert_id)) {
        return false;
    }
    if (!empty($notes[$type]["ext"])) {
        foreach ($notes[$type]["ext"] as $val) {
            if ($type == "place_order" && in_array($order["order_type"], array(3, 4))) {
                continue;
            }
            if ($val["key"] == "pay_time_limit" && !$config_takeout["pay_time_limit"]) {
                unset($val["note"]);
            } else {
                if ($val["key"] == "handle_time_limit" && empty($config_takeout["handle_time_limit"])) {
                    unset($val["note"]);
                }
            }
            $data = array("uniacid" => $_W["uniacid"], "oid" => $id, "title" => $val["title"], "note" => $val["note"], "addtime" => TIMESTAMP, "hash" => random(20));
            pdo_insert('tiny_wmall_order_status_log', $data);
        }
    }
    return true;
}
function cart_data_init($sid, $goods_id = 0, $option_key = 0, $sign = "", $ignore_bargain = false, $extra = array("avtivitycron" => 1))
{
    global $_W;
    global $_GPC;
    mload()->model('goods');
    $cart = order_fetch_member_cart($sid, false);
    $svip_buy_show = 1;
    $goods_ids = array();
    if (!empty($cart)) {
        $goods_ids = array_keys($cart["data"]);
        $svip_buy_show = 2;
    }
    if (0 < $goods_id) {
        $svip_buy_show = 3;
    }
    $goods_ids[] = $goods_id;
    $goods_ids_str = implode(",", $goods_ids);
    $buy_huangou_goods = intval($_GPC["buy_huangou_goods"]);
    if ($buy_huangou_goods == 1) {
        $goods_info = pdo_fetchall("SELECT * FROM " . tablename("tiny_wmall_goods") . " WHERE uniacid = :uniacid AND sid = :sid AND id IN (" . $goods_ids_str . ")", array(":uniacid" => $_W["uniacid"], ":sid" => $sid), "id");
    } else {
        $goods_info = pdo_fetchall("SELECT * FROM " . tablename("tiny_wmall_goods") . " WHERE uniacid = :uniacid AND sid = :sid and huangou_type = :huangou_type AND id IN (" . $goods_ids_str . ")", array(":uniacid" => $_W["uniacid"], ":sid" => $sid, ":huangou_type" => 1), "id");
    }
    $group_id = $_W["member"]["kabao"]["status"] == 1 && $_W["member"]["kabao"]["vip_goods"] == 1 ? $_W["member"]["kabao"]["group_id"] : 0;
    if (!empty($goods_info)) {
        $goods_categorys = pdo_fetchall("select id, is_showtime, start_time, end_time, week, status from " . tablename("tiny_wmall_goods_category") . "where uniacid = :uniacid and sid = :sid", array(":uniacid" => $_W["uniacid"], ":sid" => $sid), "id");
        $config_svip_status = svip_status_is_available();
        $member_svip_status = 0;
        $buysvip = 0;
        if ($config_svip_status) {
            $buysvip = intval($_GPC["is_buysvip"]);
            if ($_W["member"]["svip_status"] == 1 || $buysvip == 1) {
                $member_svip_status = 1;
            }
        }
        foreach ($goods_info as $key => &$value) {
            if ($member_svip_status == 1 && $value["svip_status"] == 1) {
                $value["origin_price"] = $value["price"];
                $value["price"] = $value["svip_price"];
            }
            if ($config_svip_status) {
                $value["config_svip_status"] = $config_svip_status;
            }
            if ($value["svip_status"] != 1 && $value["kabao_status"] == 1 && 0 < $group_id) {
                $value["group_id"] = $group_id;
                $value["kabao_price_all"] = iunserializer($value["kabao_price"]);
                if (!empty($value["kabao_price_all"][$group_id])) {
                    $value["price"] = floatval($value["kabao_price_all"][$group_id]["kabao_price"]);
                }
            } else {
                $value["kabao_status"] = 0;
            }
            if (ORDER_TYPE == 'tangshi') {
                $value["price"] = $value["ts_price"];
            }
            if (ORDER_TYPE == 'takeout' && $value["type"] == 2 || ORDER_TYPE == "tangshi" && $value["type"] == 1) {
                unset($goods_info[$key]);
            }
            $goods_category = $goods_categorys[$value["cid"]];
            $value["c_status"] = $goods_category["status"];
            $value["c_is_showtime"] = $goods_category["is_showtime"];
            $value["c_start_time"] = $goods_category["start_time"];
            $value["c_end_time"] = $goods_category["end_time"];
            $value["c_week"] = $goods_category["week"];
            if (empty($value["is_showtime"]) && !empty($value["c_is_showtime"])) {
                $value["is_showtime"] = $value["c_is_showtime"];
                $value["start_time1"] = $value["c_start_time"];
                $value["end_time1"] = $value["c_end_time"];
                $value["week"] = $value["c_week"];
            }
        }
    }
    $options = pdo_fetchall("select * from " . tablename("tiny_wmall_goods_options") . " where uniacid = :uniacid and sid = :sid and goods_id in (" . $goods_ids_str . ") ", array(":uniacid" => $_W["uniacid"], ":sid" => $sid));
    if (!empty($options)) {
        foreach ($options as $option) {
            $option["kabao_price_all"] = iunserializer($option["kabao_price"]);
            $goods_info[$option["goods_id"]]["options"][$option["id"]] = $option;
        }
    }
    $bargain_goods_ids = array();
    if (!$ignore_bargain) {
        mload()->model("activity");
        if (empty($extra["avtivitycron"])) {
            activity_store_cron($sid);
        }
        $bargains = pdo_getall("tiny_wmall_activity_bargain", array("uniacid" => $_W["uniacid"], "sid" => $sid, "status" => "1"), array(), "id");
        if ($buy_huangou_goods == 1) {
            $bargains_huangou = pdo_get("tiny_wmall_activity_bargain", array("uniacid" => $_W["uniacid"], "sid" => $sid, "type" => "huangou"), array());
            if (!empty($bargains_huangou)) {
                $bargains[$bargains_huangou["id"]] = $bargains_huangou;
            }
        }
        if (!empty($bargains)) {
            $bargain_ids = implode(",", array_keys($bargains));
            $bargain_goods = pdo_fetchall("select * from " . tablename("tiny_wmall_activity_bargain_goods") . " where uniacid = :uniacid and sid = :sid and bargain_id in (" . $bargain_ids . ")", array(":uniacid" => $_W["uniacid"], ":sid" => $sid));
            $bargain_goods_group = array();
            if (!empty($bargain_goods)) {
                foreach ($bargain_goods as &$row) {
                    $bargain_goods_ids[$row["goods_id"]] = $row["bargain_id"];
                    $row["available_buy_limit"] = $row["max_buy_limit"];
                    $bargain_goods_group[$row["bargain_id"]][$row["goods_id"]] = $row;
                }
            }
            $where = " where uniacid = :uniacid and sid = :sid and uid = :uid and stat_day = :stat_day and status < :status and bargain_id in (" . $bargain_ids . ") group by bargain_id";
            $params = array(":uniacid" => $_W["uniacid"], ":sid" => $sid, ":stat_day" => date("Ymd"), ":uid" => $_W["member"]["uid"], ":status" => 6);
            $bargain_order = pdo_fetchall("select count(distinct(oid)) as num, bargain_id from " . tablename("tiny_wmall_order_stat") . $where, $params, "bargain_id");
            foreach ($bargains as &$row) {
                $row["available_goods_limit"] = $row["goods_limit"];
                $row["goods"] = $bargain_goods_group[$row["id"]];
                $row["avaliable_order_limit"] = $row["order_limit"];
                if (!empty($bargain_order) && $row["type"] != "huangou") {
                    $row["avaliable_order_limit"] = $row["order_limit"] - intval($bargain_order[$row["id"]]["num"]);
                }
                $row["hasgoods"] = array();
            }
        } else {
            $bargains = array();
        }
    }
    $total_num = 0;
    $total_original_price = 0;
    $total_price = 0;
    $total_box_price = 0;
    $cart_bargain = array();
    $bargain_has_goods = array();
    $total_huangou_price = 0;
    if (!empty($cart)) {
        foreach ($cart["data"] as $k => $v) {
            $k = intval($k);
            $goods = $goods_info[$k];
            if (empty($goods) || $k == "88888") {
                continue;
            }
            if (!goods_is_available($goods)) {
                unset($cart["data"][$k]);
                unset($cart["original_data"][$k]);
                continue;
            }
            if ($goods["is_options"] == 1 && empty($goods["options"])) {
                $goods["is_options"] = 0;
            }
            $goods_box_price = $goods["box_price"];
            if (!$goods["is_options"]) {
                $discount_num = 0;
                foreach ($v as $key => $val) {
                    $goods["options_data"] = goods_build_options($goods);
                    $key = trim($key);
                    $option = $goods["options_data"][$key];
                    if (empty($option) || empty($option["total"])) {
                        continue;
                    }
                    $num = intval($val["num"]);
                    if ($option["total"] != -1 && $option["total"] <= $num) {
                        $num = $option["total"];
                    }
                    if ($num < $goods["unitnum"]) {
                        continue;
                    }
                    if (0 < $option["total"] && $option["total"] < $goods["unitnum"]) {
                        continue;
                    }
                    if ($num <= 0) {
                        continue;
                    }
                    if ($goods["total"] != -1) {
                        $goods["total"] -= $num;
                        $goods["total"] = max($goods["total"], 0);
                    }
                    $title = $goods_info[$k]["title"];
                    if (!empty($key)) {
                        $title = (string) $title . "(" . $option["name"] . ")";
                    }
                    $cart_item = array("cid" => $goods_info[$k]["cid"], "child_id" => $goods_info[$k]["child_id"], "goods_id" => $k, "thumb" => tomedia($goods_info[$k]["thumb"]), "title" => $title, "option_title" => $option["name"], "num" => $num, "price" => $goods_info[$k]["price"], "discount_price" => $goods_info[$k]["price"], "discount_num" => 0, "price_num" => $num, "total_price" => round($goods_info[$k]["price"] * $num, 2), "total_discount_price" => round($goods_info[$k]["price"] * $num, 2), "bargain_id" => 0, "buy_svip_status" => intval($option["svip_status"]) && $buysvip, "is_svip_price" => $member_svip_status, "total_price_show" => 0, "total_discount_price_show" => 0, "total_huangou_price" => 0, "is_show" => 1);
                    $cart_item["total_price_show"] = $cart_item["total_price"];
                    $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"];
                    if ($svip_buy_show != 2 && $option["svip_status"] == 1 && empty($buysvip)) {
                        $svip_buy_show = 0;
                    }
                    if (in_array($k, array_keys($bargain_goods_ids))) {
                        $goods_bargain_id = $bargain_goods_ids[$k];
                        $bargain = $bargains[$goods_bargain_id];
                        $bargain_goods = $bargain["goods"][$k];
                        $option_discount_num_cart = $val["discount_num"];
                        $val["discount_num"] = min($bargain_goods["max_buy_limit"], $num);
                        if ($bargain["type"] == "huangou") {
                            $val["discount_num"] = min($bargain_goods["max_buy_limit"], $option_discount_num_cart);
                        }
                        if ($bargain["type"] == "bargain" && 0 < $bargain["avaliable_order_limit"] && 0 < $bargain["available_goods_limit"] && 0 < $bargain_goods["available_buy_limit"] || $bargain["type"] == "huangou" && 0 < $val["discount_num"] && 0 < $bargain["available_goods_limit"] && 0 < $bargain_goods["available_buy_limit"]) {
                            $i = 0;
                            while ($i < $val["discount_num"]) {
                                if ($bargain_goods["poi_user_type"] == "new" && empty($_W["member"]["is_store_newmember"])) {
                                    break;
                                }
                                if (($bargain_goods["discount_available_total"] == -1 || 0 < $bargain_goods["discount_available_total"]) && 0 < $bargain_goods["available_buy_limit"]) {
                                    $cart_item["discount_price"] = $bargain_goods["discount_price"];
                                    $cart_item["discount_num"]++;
                                    $cart_item["bargain_id"] = $bargain["id"];
                                    $cart_bargain[] = $bargain["use_limit"];
                                    if (0 < $cart_item["price_num"]) {
                                        $cart_item["price_num"]--;
                                    }
                                    if (0 < $bargain_goods["discount_available_total"]) {
                                        $bargain_goods["discount_available_total"]--;
                                        $bargains[$goods_bargain_id]["goods"][$k]["discount_available_total"]--;
                                    }
                                    $bargain_goods["available_buy_limit"]--;
                                    $bargains[$goods_bargain_id]["goods"][$k]["available_buy_limit"]--;
                                    $discount_num++;
                                    $bargain_has_goods[] = $k;
                                    $cart_item["bargain_type"] = $bargain["type"];
                                    $i++;
                                } else {
                                    break;
                                }
                            }
                            $cart_item["total_discount_price"] = $cart_item["discount_num"] * $bargain_goods["discount_price"] + $cart_item["price_num"] * $goods_info[$k]["price"];
                            $cart_item["total_discount_price"] = round($cart_item["total_discount_price"], 2);
                            $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"];
                            if ($bargain["type"] == "huangou") {
                                $cart_item["total_huangou_price"] = round($cart_item["discount_num"] * $bargain_goods["discount_price"], 2);
                                $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"] - $cart_item["total_huangou_price"];
                                $cart_item["total_price_show"] = round($goods_info[$k]["price"] * ($num - $cart_item["discount_num"]), 2);
                                if ($cart_item["discount_num"] == $cart_item["num"]) {
                                    $cart_item["is_show"] = 0;
                                }
                            }
                        }
                    } else {
                        if ($goods["svip_status"] == 1 && $member_svip_status == 1) {
                            $cart_item["total_price"] = round($goods["origin_price"] * $num, 2);
                        }
                    }
                    $total_num += $num;
                    $total_price += $cart_item["total_discount_price"];
                    $total_original_price += $cart_item["total_price"];
                    $total_box_price += $goods_box_price * $num;
                    $cart_goods[$k][$key] = $cart_item;
                    $total_huangou_price += $cart_item["total_huangou_price"];
                }
                if (0 < $discount_num) {
                    $bargain["available_goods_limit"]--;
                    $bargains[$goods_bargain_id]["available_goods_limit"]--;
                }
                $totalnum = get_cart_goodsnum($k, -1, "num", $cart_goods);
                if ($goods_info[$k]["total"] != -1) {
                    $goods_info[$k]["total"] -= $totalnum;
                    $goods_info[$k]["total"] = max($goods_info[$k]["total"], 0);
                }
            } else {
                foreach ($v as $key => $val) {
                    $goods["options_data"] = goods_build_options($goods);
                    $option_id = tranferOptionid($key);
                    $key = trim($key);
                    $option = $goods["options_data"][$key];
                    if (empty($option) || empty($option["total"])) {
                        continue;
                    }
                    $num = intval($val["num"]);
                    if ($option["total"] != -1 && $option["total"] <= $num) {
                        $num = $option["total"];
                    }
                    if ($num < $goods["unitnum"]) {
                        continue;
                    }
                    if (0 < $option["total"] && $option["total"] < $goods["unitnum"]) {
                        continue;
                    }
                    if ($num <= 0) {
                        continue;
                    }
                    if ($goods["options"][$option_id]["total"] != -1) {
                        $goods["options"][$option_id]["total"] -= $num;
                        $goods["options"][$option_id]["total"] = max($goods["options"][$option_id]["total"], 0);
                    }
                    $title = $goods_info[$k]["title"];
                    if (!empty($key)) {
                        $title = (string) $title . "(" . $option["name"] . ")";
                    }
                    $cart_goods[$k][$key] = array("cid" => $goods_info[$k]["cid"], "child_id" => $goods_info[$k]["child_id"], "goods_id" => $k, "thumb" => tomedia($goods_info[$k]["thumb"]), "title" => $title, "option_title" => $option["name"], "num" => $num, "price" => $option["price"], "discount_price" => $option["price"], "discount_num" => 0, "price_num" => $num, "total_price" => round($option["price"] * $num, 2), "total_discount_price" => round($option["price"] * $num, 2), "bargain_id" => 0, "buy_svip_status" => intval($option["svip_status"]) && $buysvip, "is_svip_price" => $member_svip_status, "is_show" => 1);
                    if ($svip_buy_show != 2 && 0 < $option["svip_price"] && $option["svip_price"] < $option["price"] && empty($buysvip)) {
                        $svip_buy_show = 0;
                    }
                    if ($option["svip_status"] == 1) {
                        $cart_goods[$k][$key]["total_price"] = round($option["origin_price"] * $num, 2);
                    }
                    $cart_goods[$k][$key]["total_price_show"] = $cart_goods[$k][$key]["total_price"];
                    $cart_goods[$k][$key]["total_discount_price_show"] = $cart_goods[$k][$key]["total_discount_price"];
                    $total_num += $num;
                    $total_price += $option["price"] * $num;
                    $total_original_price += $cart_goods[$k][$key]["total_price"];
                    $total_box_price += $goods_box_price * $num;
                    if ($goods_info[$k]["options"][$option_id]["total"] != -1) {
                        $goods_info[$k]["options"][$option_id]["total"] -= $num;
                        $goods_info[$k]["options"][$option_id]["total"] = max($goods_info[$k]["options"][$option_id]["total"], 0);
                    }
                }
            }
        }
    }
    $goods_item = $goods_info[$goods_id];
    $goods_item["options_data"] = goods_build_options($goods_item);
    if ($goods_item["unitnum"] <= 0) {
        $goods_item["unitnum"] = 1;
    }
    $option_key = trim($option_key);
    if (empty($option_key)) {
        $option_key = 0;
    }
    $cart_item = $cart_goods[$goods_id][$option_key];
    if ($sign == "+") {
        if (!goods_is_available($goods_info[$goods_id])) {
            return error(-1, "当前商品不在可售时间范围内");
        }
        $option = $goods_item["options_data"][$option_key];
        if (empty($option["total"]) || $option["total"] < -1) {
            return error(-1, "库存不足");
        }
        if (empty($cart_item)) {
            if (1 < $goods_item["unitnum"] && 0 < $option["total"] && $option["total"] < $goods_item["unitnum"]) {
                return error(-1, "库存不足");
            }
            $title = $goods_item["title"];
            if (!empty($option_key)) {
                $title = (string) $title . "(" . $option["name"] . ")";
            }
            $cart_item = array("cid" => $goods_info[$goods_id]["cid"], "child_id" => $goods_info[$goods_id]["child_id"], "goods_id" => $goods_id, "thumb" => tomedia($goods_info[$goods_id]["thumb"]), "title" => $title, "option_title" => $option["name"], "num" => 0, "price" => $option["price"], "discount_price" => $option["price"], "discount_num" => 0, "price_num" => 0, "total_price" => 0, "total_discount_price" => 0, "bargain_id" => 0, "buy_svip_status" => intval($option["svip_status"]) && $buysvip, "is_svip_price" => $member_svip_status, "total_price_show" => 0, "total_discount_price_show" => 0, "total_huangou_price" => 0, "is_show" => 1);
            if ($svip_buy_show != 2) {
                if ($goods_info[$goods_id]["is_options"]) {
                    if (0 < $option["svip_price"] && $option["svip_price"] < $option["price"] && empty($buysvip)) {
                        $svip_buy_show = 0;
                    }
                } else {
                    if ($option["svip_status"] == 1 && empty($buysvip)) {
                        $svip_buy_show = 0;
                    }
                }
            }
        }
        if ($cart_item["num"] == 0) {
            for ($i = 0; $i < $goods_item["unitnum"]; $i++) {
                $price_change = 0;
                $price = $option["price"];
                if (in_array($goods_id, array_keys($bargain_goods_ids))) {
                    $goods_bargain_id = $bargain_goods_ids[$goods_id];
                    $bargain = $bargains[$goods_bargain_id];
                    $bargain_goods = $bargain["goods"][$goods_id];
                    if ($bargain["type"] == "huangou") {
                        if ($bargain["available_goods_limit"] <= 0 && !in_array($goods_id, $bargain_has_goods)) {
                            return error(-1, "换购限" . $bargain["goods_limit"] . "种商品");
                        }
                        if ($bargain_goods["discount_available_total"] != -1 && $bargain_goods["discount_available_total"] == 0) {
                            return error(-1, "换购商品库存不足");
                        }
                    }
                    $msg = "";
                    $pricenum = get_cart_goodsnum($goods_id, "-1", "price_num", $cart_goods);
                    if ($bargain_goods["poi_user_type"] == "new" && !$_W["member"]["is_store_newmember"]) {
                        if (!$pricenum) {
                            $msg = "仅限门店新用户优惠";
                        }
                        $price_change = 1;
                        $price = $option["price"];
                    }
                    if (!$price_change && $bargain["avaliable_order_limit"] <= 0) {
                        if (!$pricenum) {
                            $msg = (string) $bargain["title"] . "活动每天限购一单,超出后恢复原价";
                        }
                        $price_change = 1;
                        $price = $option["price"];
                    }
                    if (!$price_change && $bargain["available_goods_limit"] <= 0 && !in_array($goods_id, $bargain_has_goods)) {
                        if (!$pricenum) {
                            $msg = (string) $bargain["title"] . "每单特价商品限购" . $bargain["goods_limit"] . "种,超出后恢复原价";
                        }
                        $price_change = 1;
                        $price = $option["price"];
                    }
                    if (!$price_change) {
                        if (!$pricenum && get_cart_goodsnum($goods_id, "-1", "discount_num", $cart_goods) == $bargain_goods["max_buy_limit"]) {
                            $msg = (string) $bargain["title"] . "每单特价商品限购" . $bargain_goods["max_buy_limit"] . "份,超出后恢复原价";
                        }
                        if ($bargain_goods["available_buy_limit"] == 0) {
                            $price_change = 1;
                            $price = $option["price"];
                        }
                        if ($bargain_goods["discount_available_total"] != -1 && $bargain_goods["discount_available_total"] == 0) {
                            if (!$pricenum) {
                                $msg = "活动库存不足,恢复原价购买";
                            }
                            $price_change = 1;
                            $price = $option["price"];
                        }
                    }
                    if (!$price_change) {
                        $price_change = 2;
                        $price = $bargain_goods["discount_price"];
                        $cart_bargain[] = $bargain["use_limit"];
                    }
                }
                if ($price_change == 2) {
                    $cart_item["discount_num"]++;
                    $cart_item["bargain_id"] = $bargain["id"];
                    $cart_item["bargain_type"] = $bargain["type"];
                    $cart_item["discount_price"] = $bargain_goods["discount_price"];
                    $bargains[$goods_bargain_id]["goods"][$goods_id]["available_buy_limit"]--;
                    if (0 < $bargains[$goods_bargain_id]["goods"][$goods_id]["discount_available_total"]) {
                        $bargains[$goods_bargain_id]["goods"][$goods_id]["discount_available_total"]--;
                    }
                } else {
                    $cart_item["price_num"]++;
                }
                $cart_item["num"]++;
                $cart_item["total_discount_price"] = $cart_item["discount_num"] * $bargain_goods["discount_price"] + $cart_item["price_num"] * $option["price"];
                $cart_item["total_discount_price"] = round($cart_item["total_discount_price"], 2);
                $cart_item["total_price"] = round($cart_item["num"] * $option["price"], 2);
                $cart_item["total_price_show"] = $cart_item["total_price"];
                $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"];
                if ($bargain["type"] == "huangou") {
                    $cart_item["total_huangou_price"] = round($cart_item["discount_num"] * $bargain_goods["discount_price"], 2);
                    $total_huangou_price = $total_huangou_price + $bargain_goods["discount_price"];
                    $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"] - $cart_item["total_huangou_price"];
                    if ($cart_item["discount_num"] == $cart_item["num"]) {
                        $cart_item["is_show"] = 0;
                    }
                }
                $total_num++;
                $total_box_price = $total_box_price + $goods_item["box_price"];
                $total_price = $total_price + $price;
                $cart_goods[$goods_id][$option_key] = $cart_item;
            }
        } else {
            $price_change = 0;
            $price = $option["price"];
            if (in_array($goods_id, array_keys($bargain_goods_ids))) {
                $goods_bargain_id = $bargain_goods_ids[$goods_id];
                $bargain = $bargains[$goods_bargain_id];
                $bargain_goods = $bargain["goods"][$goods_id];
                if ($bargain["type"] == "huangou") {
                    if ($bargain["available_goods_limit"] <= 0 && !in_array($goods_id, $bargain_has_goods)) {
                        return error(-1, "换购限" . $bargain["goods_limit"] . "种商品");
                    }
                    if ($bargain_goods["available_buy_limit"] <= 0) {
                        return error(-1, "换购商品限购" . $bargain_goods["max_buy_limit"] . "份");
                    }
                    if ($bargain_goods["discount_available_total"] != -1 && $bargain_goods["discount_available_total"] == 0) {
                        return error(-1, "换购商品库存不足");
                    }
                }
                $msg = "";
                $pricenum = get_cart_goodsnum($goods_id, "-1", "price_num", $cart_goods);
                if ($bargain_goods["poi_user_type"] == "new" && !$_W["member"]["is_store_newmember"]) {
                    if (!$pricenum) {
                        $msg = "仅限门店新用户优惠";
                    }
                    $price_change = 1;
                    $price = $option["price"];
                }
                if (!$price_change && $bargain["avaliable_order_limit"] <= 0) {
                    if (!$pricenum) {
                        $msg = (string) $bargain["title"] . "活动每天限购一单,超出后恢复原价";
                    }
                    $price_change = 1;
                    $price = $option["price"];
                }
                if (!$price_change && $bargain["available_goods_limit"] <= 0 && !in_array($goods_id, $bargain_has_goods)) {
                    if (!$pricenum) {
                        $msg = (string) $bargain["title"] . "每单特价商品限购" . $bargain["goods_limit"] . "种,超出后恢复原价";
                    }
                    $price_change = 1;
                    $price = $option["price"];
                }
                if (!$price_change) {
                    if (!$pricenum && get_cart_goodsnum($goods_id, "-1", "discount_num", $cart_goods) == $bargain_goods["max_buy_limit"]) {
                        $msg = (string) $bargain["title"] . "每单特价商品限购" . $bargain_goods["max_buy_limit"] . "份,超出后恢复原价";
                    }
                    if ($bargain_goods["available_buy_limit"] <= 0) {
                        $price_change = 1;
                        $price = $option["price"];
                    }
                    if ($bargain_goods["discount_available_total"] != -1 && $bargain_goods["discount_available_total"] == 0) {
                        if (!$pricenum) {
                            $msg = "活动库存不足,恢复原价购买";
                        }
                        $price_change = 1;
                        $price = $option["price"];
                    }
                }
                if (!$price_change) {
                    $price_change = 2;
                    $price = $bargain_goods["discount_price"];
                    $cart_bargain[] = $bargain["use_limit"];
                }
            }
            if ($price_change == 2) {
                $cart_item["discount_num"]++;
                $cart_item["bargain_id"] = $bargain["id"];
                $cart_item["bargain_type"] = $bargain["type"];
                $cart_item["discount_price"] = $bargain_goods["discount_price"];
            } else {
                $cart_item["price_num"]++;
            }
            $cart_item["num"]++;
            $cart_item["total_discount_price"] = $cart_item["discount_num"] * $bargain_goods["discount_price"] + $cart_item["price_num"] * $option["price"];
            $cart_item["total_discount_price"] = round($cart_item["total_discount_price"], 2);
            $cart_item["total_price"] = round($cart_item["num"] * $option["price"], 2);
            $total_num++;
            $total_box_price = $total_box_price + $goods_item["box_price"];
            $total_price = $total_price + $price;
            $cart_item["total_price_show"] = $cart_item["total_price"];
            $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"];
            if ($bargain["type"] == "huangou") {
                $cart_item["total_huangou_price"] = round($cart_item["discount_num"] * $bargain_goods["discount_price"], 2);
                $total_huangou_price = $total_huangou_price + $bargain_goods["discount_price"];
                $cart_item["total_price_show"] = round($option["price"] * ($cart_item["num"] - $cart_item["discount_num"]), 2);
                $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"] - $cart_item["total_huangou_price"];
                if ($cart_item["discount_num"] == $cart_item["num"]) {
                    $cart_item["is_show"] = 0;
                }
            }
        }
    } else {
        if (!empty($cart_item) && 0 < $cart_item["num"]) {
            if ($cart_item["num"] <= $goods_item["unitnum"]) {
                if ($buy_huangou_goods == 1 && $cart_item["discount_num"] <= 0) {
                    return error(-1, "已经没有换购商品");
                }
                $total_num = $total_num - $cart_item["num"];
                $total_box_price = $total_box_price - $goods_item["box_price"] * $cart_item["num"];
                $total_price = $total_price - $cart_item["total_discount_price"];
                $cart_item["num"] = 0;
                $total_huangou_price = 0;
            } else {
                $cart_item["num"]--;
                $price = $cart_item["price"];
                if (empty($buy_huangou_goods)) {
                    if (0 < $cart_item["price_num"]) {
                        $cart_item["price_num"]--;
                    } else {
                        if (0 < $cart_item["discount_num"]) {
                            $price = $cart_item["discount_price"];
                            $cart_item["discount_num"]--;
                            if ($cart_item["discount_num"] <= 0) {
                                $cart_item["bargain_id"] = 0;
                            }
                        }
                    }
                } else {
                    if ($cart_item["discount_num"] <= 0) {
                        return error(-1, "已经没有换购商品");
                    }
                    $price = $cart_item["discount_price"];
                    $cart_item["discount_num"]--;
                    $cart_item["total_huangou_price"] = round($cart_item["discount_num"] * $cart_item["discount_price"], 2);
                    $total_huangou_price = $total_huangou_price - $cart_item["discount_price"];
                    if ($cart_item["discount_num"] <= 0) {
                        $cart_item["bargain_id"] = 0;
                    }
                }
                $cart_item["total_price"] = round($cart_item["num"] * $cart_item["price"], 2);
                $cart_item["total_discount_price"] = $cart_item["discount_num"] * $cart_item["discount_price"] + $cart_item["price_num"] * $cart_item["price"];
                $cart_item["total_discount_price"] = round($cart_item["total_discount_price"], 2);
                $total_num--;
                $total_box_price = $total_box_price - $goods_item["box_price"];
                $total_price = $total_price - $price;
                $cart_item["total_price_show"] = $cart_item["total_price"];
                $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"];
                if ($buy_huangou_goods == 1) {
                    $cart_item["total_price_show"] = round($cart_item["price_num"] * $cart_item["price"], 2);
                    $cart_item["total_discount_price_show"] = $cart_item["total_discount_price"] - $cart_item["total_huangou_price"];
                    if ($cart_item["discount_num"] == $cart_item["num"]) {
                        $cart_item["is_show"] = 0;
                    }
                }
            }
        }
    }
    $box_price_cn = store_get_data($sid, "cn.box_price");
    if (0 < $total_box_price && ORDER_TYPE != "tangshi") {
        $cart_goods["88888"] = array(array("num" => 0, "title" => empty($box_price_cn) ? "餐盒费" : $box_price_cn, "goods_id" => "88888", "discount_num" => 0, "price_num" => 0, "price_total" => $total_box_price, "total_discount_price" => $total_box_price));
    }
    if ($sign) {
        $cart_goods[$goods_id][$option_key] = $cart_item;
        if ($sign == "-") {
            $buysvip = 0;
            foreach ($cart_goods[$goods_id] as $key => &$item) {
                if (!$item["num"]) {
                    unset($cart_goods[$goods_id][$key]);
                }
            }
            foreach ($cart_goods as $val) {
                foreach ($val as $v) {
                    if ($v["buy_svip_status"]) {
                        $buysvip = 1;
                        break 2;
                    }
                }
            }
            $item_total_num = get_cart_goodsnum($goods_id, -1, "num", $cart_goods);
            if (!$item_total_num) {
                unset($cart_goods[$goods_id]);
            }
        }
    }
    $isexist = pdo_fetchcolumn("SELECT id FROM " . tablename("tiny_wmall_order_cart") . " WHERE uniacid = :aid AND sid = :sid AND uid = :uid", array(":aid" => $_W["uniacid"], ":sid" => $sid, ":uid" => $_W["member"]["uid"]));
    $cart_goods_original = array();
    foreach ($cart_goods as $key => $row) {
        $cart_goods_original[$key] = array("title" => $goods_info[$key]["title"], "goods_id" => $key, "options" => $row);
    }
    $data = array("uniacid" => $_W["uniacid"], "sid" => $sid, "uid" => $_W["member"]["uid"], "groupid" => $_W["member"]["groupid"], "num" => $total_num, "price" => round($total_price, 2), "box_price" => round($total_box_price, 2), "data" => iserializer($cart_goods), "original_data" => iserializer($cart_goods_original), "addtime" => TIMESTAMP, "bargain_use_limit" => 0);
    if (!empty($cart_bargain)) {
        $cart_bargain = array_unique($cart_bargain);
        if (in_array(1, $cart_bargain)) {
            $data["bargain_use_limit"] = 1;
        }
        if (in_array(2, $cart_bargain)) {
            $data["bargain_use_limit"] = 2;
        }
    }
    $data["is_buysvip"] = $buysvip;
    if ($_W["member"]["svip_status"] == 1) {
        $data["is_buysvip"] = 0;
    }
    if (empty($isexist)) {
        pdo_insert("tiny_wmall_order_cart", $data);
        $data["id"] = pdo_insertid();
    } else {
        pdo_update('tiny_wmall_order_cart', $data, array("uniacid" => $_W["uniacid"], "id" => $isexist, "uid" => $_W["member"]["uid"]));
        $data["id"] = $cart["id"];
    }
    if (empty($bargain_has_goods)) {
        $discount_notice = array();
        $store_discount = pdo_get("tiny_wmall_store_activity", array("uniacid" => $_W["uniacid"], "sid" => $sid, "type" => "discount", "status" => 1), array("title", "data"));
        if (!empty($store_discount)) {
            $discount_notice["note"] = $store_discount["title"];
            if (0 < $data["price"]) {
                $discount_condition = iunserializer($store_discount["data"]);
                $apply_price = array_keys($discount_condition);
                sort($apply_price);
                foreach ($apply_price as $key => $val) {
                    if ($val < $data["price"] && $apply_price[$key + 1]) {
                        continue;
                    }
                    $dvalue = round($val - $data["price"], 2);
                    if ($dvalue <= 5 || 0 < $key) {
                        $discount_notice["leave_price"] = $dvalue;
                        $discount_notice["back_price"] = $discount_condition[$val]["back"];
                        $discount_notice["note"] = 0 < $dvalue ? "再买 " . $dvalue . " " . $_W["Lang"]["dollarSignCn"] . ", 可减 " . $discount_notice["back_price"] . " " . $_W["Lang"]["dollarSignCn"] : "下单减 " . $discount_notice["back_price"] . " " . $_W["Lang"]["dollarSignCn"];
                        if (0 < $key && 0 < $dvalue) {
                            $discount_notice["note"] = "下单减 " . $discount_condition[$apply_price[$key - 1]]["back"] . " " . $_W["Lang"]["dollarSignCn"] . " " . $discount_notice["note"];
                        }
                        if ($data["price"] < $apply_price[$key + 1]) {
                            if ($dvalue <= 0) {
                                $furdiscount = $apply_price[$key + 1] - $data["price"];
                                $discount_notice["note"] .= ", 再买 " . $furdiscount . " " . $_W["Lang"]["dollarSignCn"] . "可减 " . $discount_condition[$apply_price[$key + 1]]["back"];
                            }
                            break;
                        }
                    }
                    if (5 < $dvalue) {
                        break;
                    }
                }
            }
        }
    }
    $data["discount_notice"] = $discount_notice;
    $data["data"] = array_values($cart_goods);
    $data["data1"] = $cart_goods;
    $data["original_data1"] = $cart_goods_original;
    $data["original_data"] = array_values($cart_goods_original);
    $data["total_huangou_price"] = round($total_huangou_price, 2);
    $data["price"] = $data["price"] - $total_huangou_price;
    $data["cart_price"] = round($data["price"] + $data["box_price"], 2);
    $category_limit = order_goods_category_limit_check($sid, $data);
    $data["is_category_limit"] = 0;
    if (is_error($category_limit)) {
        $data["is_category_limit"] = 1;
        $data["category_limit_cn"] = $category_limit["message"];
    }
    $data["svip_buy_show"] = intval($svip_buy_show);
    $data["pindan_id"] = intval($cart["pindan_id"]);
    $result = array("cart" => $data, "msg" => $msg);
    return error(0, $result);
}