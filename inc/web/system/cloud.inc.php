<?php

/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/

defined('IN_IA') || exit('Access Denied');
mload()->model('cloud');
global $_W;
global $_GPC;
$op = (trim($_GPC['op']) ? trim($_GPC['op']) : 'auth');
if ('auth' === $op) {
    $_W['page']['title'] = '授权管理';
}

if ('upgrade' === $op) {
    $_W['page']['title'] = '系统更新';
    $auth_url = '/web/index.php?c=mod&a=mod_wmall&';
}

include itemplate('system/cloud');

?>
