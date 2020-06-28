<?php

defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
if ($op == 'list') {
    $_W['page']['title'] = '聊天管理';
    $condition = ' where uniacid = :uniacid';
    $params = array(':uniacid' => $_W['uniacid']);
    $relation = trim($_GPC['relation']);
    if (!empty($relation)) {
        $condition .= ' and relation = :relation ';
        $params[':relation'] = $relation;
    }
    $days = isset($_GPC['days']) ? intval($_GPC['days']) : -2;
    $todaytime = strtotime(date('Y-m-d'));
    $starttime = $todaytime;
    $endtime = $starttime + 86399;
    if ($days > -2) {
        if ($days == -1) {
            $starttime = strtotime($_GPC['lasttime']['start']);
            $endtime = strtotime($_GPC['lasttime']['end']);
            $condition .= " and lasttime > :start and lasttime < :end";
            $params[':start'] = $starttime;
            $params[':end'] = $endtime;
        } else {
            $starttime = strtotime("-{$days} days", $todaytime);
            $condition .= ' and lasttime >= :start';
            $params[':start'] = $starttime;
        }
    }
    $keywords = trim($_GPC['keywords']);
    if (!empty($keywords)) {
        $condition .= " and (fansnickname like '%{$keywords}%' or kefunickname like '%{$keywords}%') ";
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = intval($_GPC['psize']) ? intval($_GPC['psize']) : 20;
    $total = intval(pdo_fetchcolumn('select count(*) from ' . tablename('tiny_wmall_kefu_chat') . $condition, $params));
    $chats = pdo_fetchall('select * from ' . tablename('tiny_wmall_kefu_chat') . $condition . ' order by lasttime desc limit ' . ($pindex - 1) * $psize . ',' . $psize, $params);
    $pager = pagination($total, $pindex, $psize);
    $relations = kefu_chat_relations();
    $chat_status = kefu_chat_status();
} elseif ($op == 'detail') {
    $_W['page']['title'] = '聊天记录';
    $id = intval($_GPC['id']);
    $chat = pdo_get('tiny_wmall_kefu_chat', array('uniacid' => $_W['uniacid'], 'id' => $id));
    if (empty($chat)) {
        imessage(error(-1, '聊天记录不存在'));
    }
    $chatlog = kefu_get_chat_log($chat['id'], array('token' => $chat['kefuopenid']));
    $result = array('chatlog' => $chatlog, 'chat' => $chat);
    if ($_W['ispost']) {
        imessage(error(0, $result), '', 'ajax');
    }
}
include itemplate('chats');