<?php

defined("IN_IA") or exit("Access Denied");
global $_W;
global $_GPC;
icheckauth();
$_W["kefu"]["user"] = $fans = array("role" => "member", "uid" => $_W["member"]["uid"], "token" => $_W["member"]["token"], "nickname" => $_W["member"]["nickname"], "avatar" => tomedia($_W["member"]["avatar"]));
$op = trim($_GPC["op"]) ? trim($_GPC["op"]) : "chat";
if ($op == "chat") {
    $relation = trim($_GPC["relation"]) ? trim($_GPC["relation"]) : "member2clerk";
    if (empty($relation)) {
        imessage(error(-1, "请选择咨询对象0！"), "", "ajax");
    }
    $kefuopenid = trim($_GPC["kefuopenid"]);
    if (empty($kefuopenid) && $relation != "member2kefu") {
        imessage(error(-1, "请选择咨询对象1！"), "", "ajax");
    }
    $kefuunionid = trim($_GPC["kefuunionid"]);
    if ($relation == "member2clerk" && empty($kefuunionid)) {
        imessage(error(-1, "请选择咨询对象2！"), "", "ajax");
    }
    $kefu = array("openid" => $kefuopenid, "unionid" => $kefuunionid);
    $orderid = intval($_GPC["orderid"]);
    $kefu = get_available_kefu($kefu, $fans, $relation, array("orderid" => $orderid));
    if (is_error($kefu)) {
        imessage($kefu, "", "ajax");
    }
    $chat = kefu_get_available_chat($kefu, $fans, $relation, array("orderid" => $orderid));
    if (is_error($chat)) {
        imessage($chat, "", "ajax");
    }
    $chatlog = kefu_get_chat_log($chat["id"], $fans);
    keft_set_notread_zero($chat, $fans);
    $result = array("chatlog" => $chatlog, "chat" => $chat, "kefu" => $kefu, "fans" => $fans, "reply" => kefu_get_fastreply($fans, $relation), "order" => kefu_get_order($orderid));
    imessage(error(0, $result), "", "ajax");
} else {
    if ($op == "addchat") {
        $chatid = intval($_GPC["chatid"]);
        $chat = kefu_get_chat($chatid);
        if (empty($chat)) {
            imessage(error(-1, "获取聊天信息失败！"), "", "ajax");
        }
        $iscanchat = kefu_check_chat_available($chat, $fansopenid);
        if (is_error($iscanchat)) {
            imessage($iscanchat, "", "ajax");
        }
        $log = kefu_add_chatlog($chat);
        $result = array("log" => $log);
        imessage(error(0, $result), "", "ajax");
    } else {
        if ($op == "addreply") {
            $relation = trim($_GPC["relation"]) ? trim($_GPC["relation"]) : "member2clerk";
            if (empty($relation)) {
                imessage(error(-1, "请选择咨询对象0！"), "", "ajax");
            }
            $content = trim($_GPC["content"]);
            if (empty($content)) {
                imessage(error(-1, "回复内容不能为空"), "", "ajax");
            }
            $status = kefu_add_fastreply($fans, $content, $relation);
            $result = array("reply" => kefu_get_fastreply($fans, $relation));
            imessage(error(0, $result), "", "ajax");
        } else {
            if ($op == "index") {
                $chats = kefu_get_mychat($fans);
                $result = array("chats" => $chats);
                imessage(error(0, $result), "", "ajax");
            } else {
                if ($op == "order") {
                    $chatid = intval($_GPC["chatid"]);
                    $orders = kefu_get_orders($chatid);
                    $result = array("orders" => $orders);
                    imessage(error(0, $result), "", "ajax");
                } else {
                    if ($op == "more") {
                        $chatid = intval($_GPC["chatid"]);
                        $chat = kefu_get_chat($chatid);
                        if (empty($chat)) {
                            imessage(error(-1, "获取聊天信息失败！"), "", "ajax");
                        }
                        $chatlog = kefu_get_chat_log($chat["id"], $fans);
                        $result = array("chatlog" => $chatlog);
                        imessage(error(0, $result), "", "ajax");
                    } else {
                        if ($op == "zero") {
                            $chatid = intval($_GPC["chatid"]);
                            $status = keft_set_notread_zero($chatid, $fans);
                            if (is_error($status)) {
                                imessage($status, "", "ajax");
                            }
                            imessage(error(0, "未读消息数已设置为0"), "", "ajax");
                        } else {
                            if ($op == "delete") {
                                $chatid = intval($_GPC["chatid"]);
                                $status = kefu_delete_chat($chatid);
                                if (empty($status)) {
                                    imessage(error(0, "会话移除失败"), "", "ajax");
                                }
                                imessage(error(0, "会话移除成功"), "", "ajax");
                            }
                        }
                    }
                }
            }
        }
    }
}