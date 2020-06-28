<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
if ($op == "list") {
    $_W["page"]["title"] = "公告列表";
    if (checksubmit('submit')) {
        if (!empty($_GPC["ids"])) {
            foreach ($_GPC["ids"] as $k => $v) {
                $data = array("title" => trim($_GPC["titles"][$k]), "link" => trim($_GPC["links"][$k]), "displayorder" => intval($_GPC["displayorders"][$k]));
                pdo_update('tiny_wmall_notice', $data, array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => intval($v)));
            }
        }
        imessage('编辑成功', iurl('merchant/notice/list'), 'success');
    }
    $condition = " where uniacid = :uniacid and type = :type and agentid = :agentid";
    $params = array(":uniacid" => $_W["uniacid"], ":agentid" => $_W["agentid"], ":type" => "store");
    $pindex = max(1, intval($_GPC["page"]));
    $psize = 15;
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_notice") . $condition, $params);
    $notices = pdo_fetchall("select * from" . tablename("tiny_wmall_notice") . $condition . " order by displayorder desc limit " . ($pindex - 1) * $psize . "," . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
}
if ($op == "post") {
    $_W["page"]["title"] = "编辑公告";
    $id = intval($_GPC["id"]);
    if (0 < $id) {
        $notice = pdo_get("tiny_wmall_notice", array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
    }
    if (empty($notice)) {
        $notice = array("status" => 1);
    }
    if ($_W["ispost"]) {
        $data = array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "title" => trim($_GPC["title"]), "content" => htmlspecialchars_decode($_GPC["content"]), "link" => trim($_GPC["link"]), "displayorder" => intval($_GPC["displayorder"]), "status" => intval($_GPC["status"]), "addtime" => TIMESTAMP, "type" => "store");
        if (!empty($notice["id"])) {
            pdo_update("tiny_wmall_notice", $data, array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
        } else {
            pdo_insert('tiny_wmall_notice', $data);
        }
        imessage(error(0, '更新公告成功'), iurl('merchant/notice/list'), 'ajax');
    }
}
if ($op == "del") {
    $id = intval($_GPC["id"]);
    pdo_delete('tiny_wmall_notice', array('uniacid' => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
    imessage(error(0, '删除公告成功'), iurl('merchant/notice/list'), 'ajax');
}
if ($op == "status") {
    $id = intval($_GPC["id"]);
    $data = array("status" => intval($_GPC["status"]));
    pdo_update('tiny_wmall_notice', $data, array("uniacid" => $_W["uniacid"], "agentid" => $_W["agentid"], "id" => $id));
    imessage(error(0, ""), "", 'ajax');
}
include itemplate('merchant/notice');