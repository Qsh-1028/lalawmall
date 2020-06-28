<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
mload()->model('store');
$_W['agentid'] = 0;
$categorys = store_fetchall_category();
ijson(error(0, $categorys));