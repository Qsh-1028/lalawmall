<?php

defined("IN_IA") or exit("Access Denied");
global $_W;
global $_GPC;
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "clerk";
require_once MODULE_ROOT . "/library/GatewayClient/Gateway.php";
GatewayClient\Gateway::$registerAddress = "127.0.0.1:1238";
$_W["kefu"]["user"] = $kefu = array("role" => "kefu", "kefu_id" => $_W["user"]["uid"], "token" => $_W["user"]["token"], "nickname" => $_W["user"]["nickname"], "avatar" => tomedia($_W["user"]["avatar"]), "kefu_status" => $_W["user"]["kefu_status"]);
if ($op == "plateform") {
    $client_id = $_GPC["client_id"];
    GatewayClient\Gateway::bindUid($client_id, $_W["kefu"]["user"]["token"]);
    imessage(error(0, ""), "", "ajax");
}