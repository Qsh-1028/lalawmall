<?php

defined('IN_IA') or exit('Access Denied');
global $_W;
global $_GPC;
mload()->model('plugin');
pload()->model('kefu');
$_W["kefu"]["user"] = $kefu = array("role" => "clerk", "clerk_id" => $_W["manager"]["id"], "token" => $_W["manager"]["token"], "unionid" => $sid, "nickname" => $_W["manager"]["nickname"], "avatar" => tomedia($_W["manager"]["avatar"]));
$ta = trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "chat";
if ($ta == "chat") {
    $relation = trim($_GPC["relation"]) ? trim($_GPC["relation"]) : "member2clerk";
    if (empty($relation)) {
        imessage(error(-1, "请选择咨询对象0！"), "", "ajax");
    }
    $fansopenid = trim($_GPC["fansopenid"]);
    if (empty($fansopenid)) {
        imessage(error(-1, "请选择咨询对象1！"), "", "ajax");
    }
    $fans = array("token" => $fansopenid);
    $touserole = kefu_get_touserrole($relation);
    $fans = kefu_get_fans($fans["token"], $touserole);
    $orderid = intval($_GPC["orderid"]);
    $chat = kefu_get_available_chat($kefu, $fans, $relation, array("orderid" => $orderid));
    if (is_error($chat)) {
        imessage($chat, "", "ajax");
    }
    $chatlog = kefu_get_chat_log($chat["id"], $kefu);
    keft_set_notread_zero($chat, $kefu);
    $result = array("chatlog" => $chatlog, "chat" => $chat, "kefu" => $kefu, "fans" => $fans, "reply" => kefu_get_fastreply($kefu, $relation), "order" => kefu_get_order($orderid));
    imessage(error(0, $result), "", "ajax");
} else {
    if ($ta == "addchat") {
        $chatid = intval($_GPC["chatid"]);
        $chat = kefu_get_chat($chatid);
        if (empty($chat)) {
            imessage(error(-1, "获取聊天信息失败！"), "", "ajax");
        }
        $iscanchat = kefu_check_chat_available($chat, $fansopenid);
        if (is_error($iscanchat)) {
            imessage($iscanchat, "", "error");
        }
        $fans = array("role" => "clerk", "token" => $_W["manager"]["token"], "nickname" => $_W["manager"]["nickname"], "avatar" => tomedia($_W["manager"]["avatar"]));
        $log = kefu_add_chatlog($chat);
        $result = array("log" => $log);
        imessage(error(0, $result), "", "ajax");
    } else {
        if ($ta == "addreply") {
            $relation = trim($_GPC["relation"]) ? trim($_GPC["relation"]) : "member2clerk";
            if (empty($relation)) {
                imessage(error(-1, "请选择咨询对象0！"), "", "ajax");
            }
            $content = trim($_GPC["content"]);
            if (empty($content)) {
                imessage(error(-1, "回复内容不能为空"), "", "ajax");
            }
            $status = kefu_add_fastreply($kefu, $content, $relation);
            $result = array("reply" => kefu_get_fastreply($kefu, $relation));
            imessage(error(0, $result), "", "ajax");
        } else {
            if ($ta == "index") {
                $chats = kefu_get_mychat($kefu);
                $result = array("chats" => $chats, "manager" => $_W["manager"]);
                imessage(error(0, $result), "", "ajax");
            } else {
                if ($ta == "order") {
                    $chatid = intval($_GPC["chatid"]);
                    $orders = kefu_get_orders($chatid);
                    $result = array("orders" => $orders);
                    imessage(error(0, $result), "", "ajax");
                } else {
                    if ($ta == "more") {
                        $chatid = intval($_GPC["chatid"]);
                        $chat = kefu_get_chat($chatid);
                        if (empty($chat)) {
                            imessage(error(-1, "获取聊天信息失败！"), "", "ajax");
                        }
                        $chatlog = kefu_get_chat_log($chat["id"], $kefu);
                        $result = array("chatlog" => $chatlog);
                        imessage(error(0, $result), "", "ajax");
                    } else {
                        if ($ta == "zero") {
                            $chatid = intval($_GPC["chatid"]);
                            $status = keft_set_notread_zero($chatid, $kefu);
                            if (is_error($status)) {
                                imessage($status, "", "ajax");
                            }
                            imessage(error(0, '未读消息数已设置为0'), "", 'ajax');
                        } else {
                            if ($ta == "delete") {
                                $chatid = intval($_GPC["chatid"]);
                                $status = kefu_delete_chat($chatid);
                                if (empty($status)) {
                                    imessage(error(0, "会话移除失败"), "", "ajax");
                                }
                                imessage(error(0, '会话移除成功'), "", 'ajax');
                            }
                        }
                    }
                }
            }
        }
    }
}