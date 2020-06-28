<?php

defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
if ($op == 'index') {
    $_W['page']['title'] = '入口链接';
    include itemplate('urls');
}