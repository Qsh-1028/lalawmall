<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "index";
if ($ta == "index") {
    $type = trim($_GPC["type"]);
    $title = trim($_GPC["title"]);
    $message = trim($_GPC["message"]);
    if (!empty($type) && !empty($message)) {
        slog($type, $title, "", $message);
    }
    imessage(error(0, ""), "", 'ajax');
}