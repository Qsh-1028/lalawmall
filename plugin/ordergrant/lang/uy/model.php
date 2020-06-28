<?php

defined('IN_IA') or exit('Access Denied');
function ordergrant_next_grant($difference)
{
    global $_W;
    $config_ordergrant = get_plugin_config("ordergrant");
    if ($config_ordergrant["status"] == 0) {
        return error(-1, "该活动未开启");
    }
    $grantType = "积分";
    if ($config_ordergrant["grantType"] == "credit2") {
        $grantType = $_W["Lang"]["dollarSignCn"];
    }
    if (0 <= $difference) {
        $time1 = strtotime(date("Ymd"));
        $time2 = strtotime(date("Ymd")) + 86400;
        $is_exist = pdo_fetch("select id from " . tablename("tiny_wmall_order_grant_record") . " where uniacid = :uniacid and uid = :uid and addtime > :time1 and addtime < :time2 and type <= 1", array(":uniacid" => $_W["uniacid"], ":uid" => $_W["member"]["uid"], ":time1" => $time1, ":time2" => $time2));
    }
    $order_days_amount = pdo_get("tiny_wmall_order_grant", array("uniacid" => $_W["uniacid"], "uid" => $_W["member"]["uid"]));
    $continuous = $order_days_amount["continuous"] + $difference;
    $sum = $order_days_amount["sum"] + $difference;
    if (empty($is_exist)) {
        $continuous = $order_days_amount["continuous"] + $difference + 1;
        $sum = $order_days_amount["sum"] + $difference + 1;
    }
    $grant = array("days" => $config_ordergrant["days_order_grant"]);
    $message = "下单即可领取日常奖励" . $grant["days"] . $grantType . "<br>";
    $text = "";
    if (!empty($config_ordergrant["continuous"])) {
        foreach ($config_ordergrant["continuous"] as $val) {
            if ($continuous == $val["days"]) {
                $condition = " where uniacid = :uniacid and uid = :uid and days = :days";
                $params = array(":uniacid" => $_W["uniacid"], ":uid" => $_W["member"]["uid"], ":days" => $continuous);
                if ($config_ordergrant["cycle"] == 1) {
                    $condition .= " and stat_month = :stat_month";
                    $params[":stat_month"] = date("Ym");
                }
                $is_exist = pdo_fetch("select id from " . tablename("tiny_wmall_order_grant_record") . $condition, $params);
                if (!empty($is_exist)) {
                    $text = "(已领取)";
                }
                $grant["continuous"] = $val["grant"];
                $message .= "连续" . $continuous . "天下单奖励" . $val["grant"] . $grantType . " " . $text . "<br>";
            }
        }
    }
    if (!empty($config_ordergrant["all"])) {
        foreach ($config_ordergrant["all"] as $val) {
            if ($sum == $val["days"]) {
                $grant["sum"] = $val["grant"];
                $message .= "累计" . $sum . "天下单奖励" . $val["grant"] . $grantType . "<br>";
            }
        }
    }
    if (!empty($config_ordergrant["special"])) {
        $special_day = TIMESTAMP + $difference * 86400;
        $special = date("Y-m-d", $special_day);
        foreach ($config_ordergrant["special"] as $val) {
            if ($special == $val["date"]) {
                $grant["special"] = $val["grant"];
                $message .= "优惠日下单奖励" . $val["grant"] . $grantType . "<br>";
            }
        }
    }
    if (empty($text)) {
        $total = array_sum($grant);
    } else {
        $total = array_sum(array($grant["days"], $grant["sum"], $grant["special"]));
    }
    $grant = array("total" => $total, "message" => $message);
    return $grant;
}
function grant_types()
{
    $labels = array(array("css" => "label-success", "text" => "日常奖励"), array("css" => "label-warning", "text" => "连续下单奖励"), array("css" => "label-info", "text" => "累计下单奖励"), array("css" => "label-primary", "text" => "首单奖励"), array("css" => "label-danger", "text" => "优惠日下单奖励"), array("css" => "label-default", "text" => "分享奖励"));
    return $labels;
}