<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
if ($op == "post") {
    $_W["page"]["title"] = "编辑幻灯片";
    $id = intval($_GPC["id"]);
    if (0 < $id) {
        $slide = pdo_get("tiny_wmall_slide", array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
        if (empty($slide)) {
            imessage("幻灯片不存在或已删除", referer(), "error");
        }
    }
    if ($_W["ispost"]) {
        $title = trim($_GPC["title"]) ? trim($_GPC["title"]) : imessage(error(-1, "标题不能为空"), "", "ajax");
        $data = array("uniacid" => $_W["uniacid"], "title" => $title, "agentid" => $_W["agentid"], "thumb" => trim($_GPC["thumb"]), "link" => trim($_GPC["link"]), "displayorder" => intval($_GPC["displayorder"]), "type" => trim($_GPC["type"]), "status" => intval($_GPC["status"]), "wxapp_link" => trim($_GPC["wxapp_link"]));
        if (!empty($slide)) {
            pdo_update("tiny_wmall_slide", $data, array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
        } else {
            pdo_insert('tiny_wmall_slide', $data);
        }
        imessage(error(0, '编辑幻灯片成功'), iurl('dashboard/slide/list'), 'ajax');
    }
}
if ($op == "list") {
    $_W["page"]["title"] = "幻灯片列表";
    if ($_W["ispost"]) {
        if (!empty($_GPC["ids"])) {
            foreach ($_GPC["ids"] as $k => $v) {
                $data = array("title" => trim($_GPC["titles"][$k]), "displayorder" => intval($_GPC["displayorders"][$k]));
                pdo_update('tiny_wmall_slide', $data, array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => intval($v)));
            }
        }
        imessage(error(0, '编辑全屏引导页成功'), iurl('dashboard/slide/list'), 'success');
    }
    $condition = " where uniacid = :uniacid and agentid = :agentid";
    $params = array(":uniacid" => $_W["uniacid"], ":agentid" => $_W["agentid"]);
    $type = isset($_GPC["type"]) ? trim($_GPC["type"]) : "";
    if (!empty($type)) {
        $condition .= " and type = :type";
        $params[":type"] = $type;
    } else {
        $condition .= " and type != :type";
        $params[":type"] = "startpage";
    }
    $pindex = max(1, intval($_GPC["page"]));
    $psize = 15;
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_slide") . $condition, $params);
    $slides = pdo_fetchall("select * from" . tablename("tiny_wmall_slide") . $condition . " order by displayorder desc limit " . ($pindex - 1) * $psize . "," . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
}
if ($op == "del") {
    $ids = $_GPC["id"];
    if (!is_array($ids)) {
        $ids = array($ids);
    }
    foreach ($ids as $id) {
        pdo_delete("tiny_wmall_slide", array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
    }
    imessage(error(0, '删除幻灯片成功'), referer(), 'ajax');
}
if ($op == "status") {
    $id = intval($_GPC["id"]);
    $status = intval($_GPC["status"]);
    pdo_update('tiny_wmall_slide', array('status' => $status), array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
    imessage(error(0, ""), "", 'ajax');
}
include itemplate('dashboard/slide');