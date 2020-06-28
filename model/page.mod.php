<?php

defined('IN_IA') or exit('Access Denied');
function store_page_get($sid, $id = 0, $mobile = true)
{
    global $_W;
    $condition = " WHERE uniacid = :uniacid and sid = :sid";
    $params = array(":uniacid" => $_W["uniacid"], ":sid" => $sid);
    if (empty($id)) {
        $condition .= " and type = :type";
        $params[":type"] = "home";
    } else {
        $condition .= " and id = :id";
        $params[":id"] = $id;
    }
    $page = pdo_fetch("SELECT * FROM " . tablename("tiny_wmall_store_page") . $condition, $params);
    if (!empty($page)) {
        $page["data"] = json_decode(base64_decode($page["data"]), true);
        foreach ($page["data"]["items"] as $itemid => &$item) {
            if (in_array($item["id"], array("picture", "banner"))) {
                foreach ($item["data"] as &$val) {
                    $val["imgurl"] = tomedia($val["imgurl"]);
                }
                if ($item["id"] == "picture" && !isset($item["params"]["picturedata"])) {
                    $item["params"]["picturedata"] = 0;
                }
            } else {
                if (in_array($item["id"], array("copyright", "img_card"))) {
                    $item["params"]["imgurl"] = tomedia($item["params"]["imgurl"]);
                } else {
                    if ($item["id"] == "searchbar" && $mobile) {
                        $item["params"]["link"] = "/pages/store/search?sid=" . $sid;
                    } else {
                        if ($item["id"] == "richtext" && $mobile) {
                            $item["params"]["content"] = base64_decode($item["params"]["content"]);
                        } else {
                            if ($item["id"] == "info" && $mobile) {
                                $store = store_fetch($sid, array("id", "title", "logo", "business_hours", "send_price", "delivery_price", "telephone", "address", "is_rest", "location_x", "location_y", "consume_per_person"));
                                $item["data"] = $store;
                            } else {
                                if ($item["id"] == "operation") {
                                    if (empty($item["params"])) {
                                        $item["params"] = array("rownum" => 4, "pagenum" => 8, "navsdata" => 0, "navsnum" => 4, "showtype" => 0, "showdot" => 0);
                                    }
                                    $item["params"]["has_diypage"] = 0;
                                    if (check_plugin_perm('diypage')) {
                                        $item["params"]["has_diypage"] = 1;
                                    } else {
                                        $item["params"]["navsdata"] = 0;
                                        $item["params"]["showtype"] = 0;
                                    }
                                    if (!isset($item["style"]["dotbackground"])) {
                                        $item["style"]["dotbackground"] = "#ff2d4b";
                                    }
                                    if ($item["params"]["navsdata"] == 1) {
                                        $categorys = store_fetchall_goods_category($sid, 1, false, "parent", "available");
                                        $categorys = array_slice($categorys, 0, $item["params"]["navsnum"]);
                                        $item["data"] = array();
                                        if (!empty($categorys)) {
                                            foreach ($categorys as $cate) {
                                                $childid = rand(1000000000, 9999999999.0);
                                                $childid = "C" . $childid;
                                                $item["data"][$childid] = array("text" => $cate["title"], "decoration" => empty($cate["description"]) ? $cate["content"] : $cate["description"], "imgurl" => tomedia($cate["thumb"]), "linkurl" => "/pages/store/goods?sid=" . $sid . "&cid=" . $cate["id"], "color" => "#333333", "dec_color" => "#a0a0a0");
                                            }
                                        }
                                    } else {
                                        foreach ($item["data"] as &$val) {
                                            $val["imgurl"] = tomedia($val["imgurl"]);
                                        }
                                    }
                                    $item["data_num"] = count($item["data"]);
                                    $item["row"] = ceil($item["params"]["pagenum"] / $item["params"]["rownum"]);
                                    if ($mobile && $item["params"]["showtype"] == 1 && $item["params"]["pagenum"] < $item["data_num"]) {
                                        $item["data"] = array_chunk($item["data"], $item["params"]["pagenum"]);
                                    }
                                } else {
                                    if ($item["id"] == "coupon" && $mobile) {
                                        $item["sid"] = $sid;
                                        mload()->model('coupon');
                                        $coupon = coupon_collect_member_available($sid);
                                        if (!empty($coupon)) {
                                            $coupon["can_collect"] = 1;
                                            $coupon["endtime_cn"] = date("Y-m-d", $coupon["endtime"]);
                                            $coupon["collect_percent"] = round($coupon["dosage"] / $coupon["amount"], 2) * 100;
                                        }
                                        $records = pdo_fetchall("select a.id,a.discount,a.condition,a.endtime,a.sid,b.title from" . tablename("tiny_wmall_activity_coupon_record") . " as a left join " . tablename("tiny_wmall_activity_coupon") . " as b on a.couponid = b.id where a.uniacid = :uniacid and a.status = 1 and a.sid = :sid and a.uid = :uid", array(":uniacid" => $_W["uniacid"], ":sid" => $sid, ":uid" => $_W["member"]["uid"]));
                                        if (!empty($records)) {
                                            foreach ($records as &$record) {
                                                $record["endtime_cn"] = date("Y-m-d", $record["endtime"]);
                                            }
                                            $coupon["record"] = $records;
                                        }
                                        $item["data"] = $coupon;
                                    } else {
                                        if ($item["id"] == "onsale" && $mobile) {
                                            $item["sid"] = $sid;
                                            if ($item["params"]["goodsdata"] == "0") {
                                                if (!empty($item["data"]) && is_array($item["data"])) {
                                                    $goodsids = array();
                                                    foreach ($item["data"] as $data) {
                                                        if (!empty($data["goods_id"])) {
                                                            $goodsids[] = $data["goods_id"];
                                                        }
                                                    }
                                                    if (!empty($goodsids)) {
                                                        $item["data"] = array();
                                                        $goodsids_str = implode(",", $goodsids);
                                                        $goods = pdo_fetchall("select a.*, b.title as store_title from " . tablename("tiny_wmall_goods") . " as a left join " . tablename("tiny_wmall_store") . " as b on a.sid = b.id where a.uniacid = :uniacid and a.sid = :sid and a.status = 1 and a.id in (" . $goodsids_str . ") order by a.displayorder desc", array(":uniacid" => $_W["uniacid"], ":sid" => $sid));
                                                    }
                                                }
                                            } else {
                                                if ($item["params"]["goodsdata"] == "1") {
                                                    $item["data"] = array();
                                                    $condition = " where a.uniacid = :uniacid and a.sid = :sid and a.status= 1";
                                                    $params = array(":uniacid" => $_W["uniacid"], ":sid" => $sid);
                                                    $limit = intval($item["params"]["goodsnum"]);
                                                    $limit = $limit ? $limit : 4;
                                                    $goods = pdo_fetchall("select a.discount_price,a.goods_id,a.discount_available_total,b.* from " . tablename("tiny_wmall_activity_bargain_goods") . " as a left join " . tablename("tiny_wmall_goods") . " as b on a.goods_id = b.id " . $condition . " order by a.mall_displayorder desc limit " . $limit, $params);
                                                    if (!empty($goods)) {
                                                        $stores = pdo_fetchall("select distinct(a.sid),b.title as store_title,b.is_rest from " . tablename("tiny_wmall_activity_bargain") . " as a left join " . tablename("tiny_wmall_store") . " as b on a.sid = b.id where a.uniacid = :uniacid and a.status = 1", array(":uniacid" => $_W["uniacid"]), "sid");
                                                    }
                                                } else {
                                                    if ($item["params"]["goodsdata"] == "2") {
                                                        $item["data"] = array();
                                                        $limit = intval($item["params"]["goodsnum"]);
                                                        $limit = $limit ? $limit : 4;
                                                        $goods = pdo_fetchall("select a.*, b.title as store_title from " . tablename("tiny_wmall_goods") . " as a left join " . tablename("tiny_wmall_store") . " as b on a.sid = b.id where a.uniacid = :uniacid and a.sid = :sid and a.status = 1 and a.is_hot = 1 order by a.displayorder desc limit " . $limit, array(":uniacid" => $_W["uniacid"], ":sid" => $sid));
                                                    }
                                                }
                                            }
                                            if (!empty($goods)) {
                                                foreach ($goods as $good) {
                                                    $childid = rand(1000000000, 9999999999.0);
                                                    $childid = "C" . $childid;
                                                    $item["data"][$childid] = array("goods_id" => $good["id"], "sid" => $good["sid"], "store_title" => $item["params"]["goodsdata"] == "1" ? $stores[$good["sid"]]["store_title"] : $good["store_title"], "thumb" => tomedia($good["thumb"]), "title" => $good["title"], "price" => $good["price"], "old_price" => $good["old_price"] ? $good["old_price"] : $good["price"], "sailed" => $good["sailed"], "unitname" => empty($good["unitname"]) ? "份" : $good["unitname"], "total" => $good["total"] != -1 ? $good["total"] : "无限", "discount" => $good["old_price"] == 0 ? 0 : round($good["price"] / $good["old_price"] * 10, 1), "comment_good_percent" => $good["comment_total"] == 0 ? 0 : round($good["comment_good"] / $good["comment_total"] * 100, 2) . "%");
                                                    if ($item["params"]["goodsdata"] == "1") {
                                                        $item["data"][$childid]["price"] = $good["discount_price"];
                                                        $item["data"][$childid]["old_price"] = $good["price"];
                                                        $item["data"][$childid]["discount"] = round($good["discount_price"] / $good["price"] * 10, 1);
                                                    } else {
                                                        if ($good["svip_status"] == 1) {
                                                            $item["data"][$childid]["svip_status"] = $good["svip_status"];
                                                            $item["data"][$childid]["svip_price"] = $good["svip_price"];
                                                            $item["data"][$childid]["price"] = $good["svip_price"];
                                                            $item["data"][$childid]["discount"] = round($good["svip_price"] / $item["data"][$childid]["old_price"] * 10, 1);
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if ($item["id"] == "evaluate" && $mobile) {
                                                $item["sid"] = $sid;
                                                $condition = " where uniacid = :uniacid and sid = :sid and status= 1 order by score desc limit 8";
                                                $params = array(":uniacid" => $_W["uniacid"], ":sid" => $sid);
                                                $item["data"] = array();
                                                $comments = pdo_fetchall("select * from " . tablename("tiny_wmall_order_comment") . $condition, $params);
                                                if (!empty($comments)) {
                                                    foreach ($comments as $comment) {
                                                        if (!empty($comment["thumbs"])) {
                                                            $comment["thumbs"] = iunserializer($comment["thumbs"]);
                                                            foreach ($comment["thumbs"] as &$val) {
                                                                $val = tomedia($val);
                                                            }
                                                        }
                                                        $comment["data"] = iunserializer($comment["data"]);
                                                        $comment["goods_title"] = array_merge($comment["data"]["good"], $comment["data"]["bad"]);
                                                        $comment["avatar"] = tomedia($comment["avatar"]);
                                                        $childid = rand(1000000000, 9999999999.0);
                                                        $childid = "C" . $childid;
                                                        $item["data"][$childid] = array("note" => $comment["note"], "thumbs" => $comment["thumbs"], "goods_title" => $comment["goods_title"], "goods_title_str" => implode(" ", $comment["goods_title"]), "mobile" => str_replace(substr($comment["mobile"], 3, 6), "******", $comment["mobile"]), "avatar" => $comment["avatar"], "reply" => $comment["reply"], "score_original" => $comment["score"], "score" => score_format($comment["score"] / 2), "replytime" => $comment["replytime"], "replytime_cn" => date("Y-m-d H:i", $comment["replytime"]), "addtime" => $comment["addtime"], "addtime_cn" => date("Y-m-d H:i", $comment["addtime"]));
                                                    }
                                                }
                                            } else {
                                                if ($item["id"] == "picturew" && !empty($item["data"])) {
                                                    foreach ($item["data"] as &$v) {
                                                        $v["imgurl"] = tomedia($v["imgurl"]);
                                                    }
                                                    $item["data_num"] = count($item["data"]);
                                                    if ($item["params"]["row"] == 1) {
                                                        $item["data"] = array_values($item["data"]);
                                                    } else {
                                                        if ($item["params"]["showtype"] == 1 && $item["params"]["pagenum"] < count($item["data"])) {
                                                            $item["data"] = array_chunk($item["data"], $item["params"]["pagenum"]);
                                                            $item["style"]["rows_num"] = ceil($item["params"]["pagenum"] / $item["params"]["row"]);
                                                            $row_base_height = array("2" => 122, "3" => 85, "4" => 65);
                                                            $item["style"]["base_height"] = $row_base_height[$item["params"]["row"]];
                                                        }
                                                    }
                                                } else {
                                                    if ($item["id"] == "gohomeActivity" && $mobile) {
                                                        mload()->model("diy");
                                                        $item["data"] = get_wxapp_gohome_goods($item, $mobile);
                                                        if (empty($item["data"])) {
                                                            unset($page["data"]["items"][$itemid]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $page;
}