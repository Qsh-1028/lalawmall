<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
icheckauth(false);
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "index";
if ($ta == "index") {
    $sid = intval($_GPC["sid"]);
    mload()->model('page');
    $store = store_fetch($sid);
    if ($_W["is_agent"]) {
        $_W["agentid"] = $store["agentid"];
    }
    $homepage = store_page_get($sid);
    if (empty($homepage)) {
        imessage(error(-1, "你需要登陆默认店铺后台-装修-门店首页 设置自定义首页后才能显示出来"), "", "ajax");
    }
    $_W["_share"] = array("title" => $store["title"], "desc" => $store["content"], "imgUrl" => tomedia($store["logo"]), "link" => ivurl("/pages/store/home", array("sid" => $sid), true));
    $result = array("homepage" => $homepage["data"], "store_id" => $sid, "config_mall" => $_config_mall, "store" => $store, "superRedpacketData" => array());
    if (check_plugin_perm('superRedpacket') && $_W["we7_wmall"]["config"]["mall"]["version"] == 2) {
        pload()->model("superRedpacket");
        $result["superRedpacketData"] = superRedpacket_grant_show();
    }
    imessage(error(0, $result), "", "ajax");
}