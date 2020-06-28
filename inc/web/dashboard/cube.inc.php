<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
$_W["page"]["title"] = "图标魔方";
if ($op == "list") {
    if (checksubmit()) {
        if (!empty($_GPC["ids"])) {
            foreach ($_GPC["ids"] as $key => $id) {
                if (intval($id)) {
                    $row = array("title" => trim($_GPC["titles"][$key]), "tips" => trim($_GPC["tips"][$key]), "link" => trim($_GPC["links"][$key]), "wxapp_link" => trim($_GPC["wxapp_links"][$key]), "thumb" => trim($_GPC["thumbs"][$key]), "displayorder" => intval($_GPC["displayorder"][$key]));
                    pdo_update('tiny_wmall_cube', $row, array("uniacid" => $_W["uniacid"], "id" => $id));
                }
            }
        }
        imessage('图片魔方设置成功', iurl('dashboard/cube/list'), 'success');
    }
    $condition = " where uniacid = :uniacid";
    $params = array(":uniacid" => $_W["uniacid"]);
    $agentid = intval($_GPC["agentid"]);
    if (0 < $agentid) {
        $condition .= " and agentid = :agentid";
        $params[":agentid"] = $agentid;
    }
    $pindex = max(1, intval($_GPC["page"]));
    $psize = 15;
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_cube") . $condition, $params);
    $cubes = pdo_fetchall("select * from " . tablename("tiny_wmall_cube") . $condition . " order by displayorder desc limit " . ($pindex - 1) * $psize . "," . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
    include itemplate('dashboard/cube');
}
if ($op == "post") {
    $id = intval($_GPC["id"]);
    if ($_W["ispost"]) {
        $updata = array("uniacid" => $_W["uniacid"], "agentid" => $_GPC["agentid"], "title" => trim($_GPC["title"]), "tips" => trim($_GPC["tips"]), "link" => trim($_GPC["link"]), "wxapp_link" => trim($_GPC["wxapp_link"]), "thumb" => trim($_GPC["thumb"]), "displayorder" => intval($_GPC["displayorder"]));
        if ($id) {
            pdo_update("tiny_wmall_cube", $updata, array("uniacid" => $_W["uniacid"], "id" => $id));
        } else {
            pdo_insert('tiny_wmall_cube', $updata);
        }
        imessage(error(0, '图片魔方设置成功'), iurl('dashboard/cube/list'), 'ajax');
    }
    if (!empty($id)) {
        $cube = pdo_get("tiny_wmall_cube", array("id" => $id));
    }
    include itemplate('dashboard/cube');
}
if ($op == "del") {
    $ids = $_GPC["id"];
    if (!is_array($ids)) {
        $ids = array($ids);
    }
    foreach ($ids as $id) {
        pdo_delete("tiny_wmall_cube", array("uniacid" => $_W["uniacid"], "id" => $id));
    }
    imessage(error(0, '删除图片魔方成功'), referer(), 'ajax');
}
if ($op == "cubeagent") {
    if ($_W["is_agent"]) {
        mload()->model("agent");
        $agents = get_agents();
    }
    $ids = $_GPC["id"];
    $ids = implode(",", $ids);
    if ($_W["ispost"] && $_GPC["set"] == 1) {
        $cubeids = explode(",", $_GPC["id"]);
        $agentid = intval($_GPC["agentid"]);
        if (0 < $agentid) {
            foreach ($cubeids as $value) {
                pdo_update("tiny_wmall_cube", array("agentid" => $agentid), array("uniacid" => $_W["uniacid"], "id" => $value));
            }
        }
        imessage(error(0, '批量操作修改成功'), iurl('dashboard/cube/list'), 'ajax');
    }
    include itemplate('dashboard/op');
}