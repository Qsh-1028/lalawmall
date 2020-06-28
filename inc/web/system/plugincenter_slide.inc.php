<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "list";
if ($op == "post") {
    $_W["page"]["title"] = "编辑幻灯片";
    $id = intval($_GPC["id"]);
    if (0 < $id) {
        $slide = pdo_get("tiny_wmall_plugincenter_slide", array("id" => $id));
        if (empty($slide)) {
            imessage("幻灯片不存在或已删除", referer(), "error");
        }
    }
    if ($_W["ispost"]) {
        $title = trim($_GPC["title"]) ? trim($_GPC["title"]) : imessage(error(-1, "标题不能为空"), "", "ajax");
        $data = array("uniacid" => 0, "title" => $title, "thumb" => trim($_GPC["thumb"]), "displayorder" => intval($_GPC["displayorder"]), "status" => intval($_GPC["status"]), "link" => trim($_GPC["link"]));
        if (!empty($id)) {
            pdo_update("tiny_wmall_plugincenter_slide", $data, array("id" => $id));
        } else {
            pdo_insert('tiny_wmall_plugincenter_slide', $data);
        }
        imessage(error(0, '编辑幻灯片成功'), iurl('system/plugincenter_slide/list'), 'ajax');
    }
    include itemplate('system/plugincenter_slide');
} else {
    if ($op == "list") {
        $_W["page"]["title"] = "幻灯片列表";
        if (checksubmit()) {
            if (!empty($_GPC["ids"])) {
                foreach ($_GPC["ids"] as $k => $v) {
                    $data = array("title" => trim($_GPC["titles"][$k]), "displayorder" => intval($_GPC["displayorders"][$k]));
                    pdo_update('tiny_wmall_plugincenter_slide', $data, array("id" => intval($v)));
                }
            }
            imessage('编辑幻灯片成功', iurl('system/plugincenter_slide/list'), 'success');
        }
        $condition = " where 1";
        $slides = pdo_fetchall("select * from" . tablename("tiny_wmall_plugincenter_slide") . $condition . " order by displayorder desc", $params);
        include itemplate('system/plugincenter_slide');
        return 1;
    } else {
        if ($op == "del") {
            $id = intval($_GPC["id"]);
            pdo_delete('tiny_wmall_plugincenter_slide', array('id' => $id));
            imessage(error(0, '删除幻灯片成功'), "", 'ajax');
        } else {
            if ($op == "status") {
                $id = intval($_GPC["id"]);
                $status = intval($_GPC["status"]);
                pdo_update('tiny_wmall_plugincenter_slide', array('status' => $status), array("id" => $id));
                imessage(error(0, ""), "", 'ajax');
            }
        }
    }
}