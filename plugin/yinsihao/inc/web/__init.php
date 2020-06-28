<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
pdo_run('delete from ' . tablename('tiny_wmall_yinsihao_bind_list') . ' where uniacid = ' . $_W["uniacid"] . " and expiration < " . TIMESTAMP);