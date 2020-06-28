<?php

defined('IN_IA') or exit('Access Denied');
function redpacket_channels()
{
    $channel = array("" => array("text" => "未知", "css" => "label-danger"), "shareRedpacket" => array("text" => "分享有礼", "css" => "label-success"), "freeLunch" => array("text" => "霸王餐", "css" => "label-info"), "superRedpacket" => array("text" => "超级红包", "css" => "label-warning"), "mealRedpacket" => array("text" => "套餐红包", "css" => "label-danger"), "mealRedpacket_plus" => array("text" => "套餐红包plus", "css" => "label-success"), "creditShop" => array("text" => "积分兑换", "css" => "label-info"), "svip" => array("text" => "超级会员", "css" => "label-warning"), "wheel" => array("text" => "大转盘", "css" => "label-success"));
    return $channel;
}