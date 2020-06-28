<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "index";
if ($ta == "index") {
    $_W["page"]["title"] = "买单";
    $condition = " WHERE a.uniacid = :uniacid and sid = :sid and is_pay = 1";
    $params = array(":uniacid" => $_W["uniacid"], ":sid" => $sid);
    $pay_type = trim($_GPC["pay_type"]);
    if (!empty($_GPC["pay_type"])) {
        $condition .= " and a.pay_type = :pay_type";
        $params[":pay_type"] = $pay_type;
    }
    $keyword = trim($_GPC["keyword"]);
    if (!empty($keyword)) {
        $condition .= " AND (b.nickname LIKE '%" . $keyword . "%' OR b.mobile LIKE '%" . $keyword . "%' OR a.order_sn LIKE '%" . $keyword . "%')";
    }
    $uid = intval($_GPC["uid"]);
    if (0 < $uid) {
        $condition .= " AND a.uid = :uid";
        $params[":uid"] = $uid;
    }
    if (!empty($_GPC["addtime"])) {
        $starttime = strtotime($_GPC["addtime"]["start"]);
        $endtime = strtotime($_GPC["addtime"]["end"]);
    } else {
        $starttime = strtotime("-7 day");
        $endtime = TIMESTAMP;
    }
    $condition .= " AND a.addtime > :start AND a.addtime < :end";
    $params[":start"] = $starttime;
    $params[":end"] = $endtime;
    $pindex = max(1, intval($_GPC["page"]));
    $psize = 15;
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_paybill_order") . " as a left join " . tablename("tiny_wmall_members") . " as b on a.uid = b.uid" . $condition, $params);
    $orders = pdo_fetchall("SELECT a.*,b.nickname,b.mobile,b.avatar FROM " . tablename("tiny_wmall_paybill_order") . " as a left join " . tablename("tiny_wmall_members") . " as b on a.uid = b.uid" . $condition . " ORDER BY addtime DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
    include itemplate('store/paycenter/paybill');
} else {
    if ($ta == "cover") {
        $_W["page"]["title"] = "买单入口链接及二维码";
        $urls = array("vue" => ivurl("/pages/store/paybill", array("sid" => $sid), true), "wxapp" => "pages/store/paybill?sid=" . $sid);
        $qrcode = array("wxapp" => store_get_data($sid, "paybill_qrcode"));
        include itemplate('store/paycenter/cover');
    } else {
        if ($ta == "wxapp_qrcode") {
            mload()->model("qrcode");
            $path = "we7_wmall/wxappqrcode/store/" . $_W["uniacid"] . "/paybill_" . $sid . ".png";
            if ($_W["ispost"]) {
                $params = array("url" => "pages/store/paybill", "scene" => "sid:" . $sid, "name" => $path);
                $res = qrcode_wxapp_build($params);
                if (is_error($res)) {
                    imessage($res, referer(), "ajax");
                }
                store_set_data($sid, "paybill_qrcode", $path);
                imessage(error(0, '生成二维码成功'), referer(), 'ajax');
            }
        }
    }
}