<?php

defined('IN_IA') or exit('Access Denied');
load()->func('communication');
class shanSong
{
    public $config = array();
    public $order = array();
    public $store = array();
    public function __construct($id = 0, $sid = 0)
    {
        mload()->model("common");
        $this->config = get_plugin_config("shansong");
        if (0 < $id) {
            $this->order = order_fetch($id);
        }
        if ($sid == 0) {
            $sid = $this->order["sid"];
        }
        if (0 < $sid) {
            $this->store = store_fetch($sid, array("id", "title", "telephone", "address", "location_x", "location_y", "data"));
        }
        if ($this->config["type"] == "store" || $this->order["delivery_type"] == 1) {
            $this->config = array_merge($this->config, $this->store["data"]["shansong"]);
        }
        $api_urls = array("open" => "http://open.ishansong.com/", "sandbox" => "http://open.s.bingex.com/");
        $this->api_url = $api_urls["open"];
    }
    public function buildParams()
    {
        global $_W;
        $order_data = $this->order["data"];
        $goods_title = "";
        $i = 0;
        foreach ($order_data["cart"] as $goods) {
            if (1 < $i) {
                break;
            }
            $goods_title .= (string) $goods["title"] . " ";
            $i++;
        }
        $goods_title .= "等" . $this->order["num"] . "件商品";
        $sender_name = $this->store["title"];
        if ($this->config["type"] == "plateform") {
            $sid = $this->store["id"];
            $sender_name = (string) $sender_name . "商户ID:" . $sid;
        }
        $params = array("partnerNo" => $this->config["partnerNO"], "order" => array("orderNo" => $this->order["ordersn"], "merchant" => array("id" => $this->config["merchantid"], "mobile" => $this->config["mobile"], "name" => $_W["we7_wmall"]["config"]["mall"]["title"], "token" => $this->config["token"]), "sender" => array("mobile" => $this->store["telephone"], "name" => $sender_name, "city" => $this->config["city"], "addr" => $this->store["address"], "addrDetail" => $this->store["address"], "lat" => $this->store["location_x"], "lng" => $this->store["location_y"]), "receiverList" => array(array("mobile" => $this->order["mobile"], "name" => $this->order["username"], "city" => $this->config["city"], "addr" => $this->order["address"], "addrDetail" => $this->order["address"], "lat" => $this->order["location_x"], "lng" => $this->order["location_y"])), "goods" => $goods_title, "weight" => 4, "addition" => 0, "remark" => $this->order["note"]));
        $params["signature"] = $sign = $this->buildSign();
        return $params;
    }
    public function buildQueryParams()
    {
        $sign = $this->buildSign();
        $params = array("partnerno" => $this->config["partnerNO"], "signature" => $sign, "mobile" => $this->config["mobile"], "orderno" => $this->order["ordersn"], "issorderno" => $this->order["data"]["shansong"]["issorderno"]);
        $str = "";
        foreach ($params as $key => $val) {
            $str .= "&" . $key . "=" . $val;
        }
        $str = ltrim($str, "&");
        return $str;
    }
    public function buildSign($params = array())
    {
        if (empty($params)) {
            $str = $this->config["partnerNO"] . $this->order["ordersn"] . $this->config["mobile"] . $this->config["md5"];
        } else {
            $str = $this->config["partnerNO"] . $params["orderno"] . $params["mobile"] . $this->config["md5"];
        }
        $sign = strtoupper(md5($str));
        return $sign;
    }
    public function httpPost($action, $params = "", $type = "post")
    {
        if ($type == "post") {
            $params = json_encode($params);
        }
        $response = ihttp_request($this->api_url . $action, $params, array("Content-Type" => "application/json"));
        if (is_error($response)) {
            return error("-2", "请求接口出错:" . $response["message"]);
        }
        $result = @json_decode($response["content"], true);
        if ($result["status"] == "ER") {
            return error(-1, "错误号：" . $result["errCode"] . ": 错误详情：" . $result["errMsg"]);
        }
        return $result["data"];
    }
    public function queryDeliveryFee()
    {
        if ($this->config["status"] != 1) {
            return error(-1, "闪送功能未开启");
        }
        $params = $this->buildParams();
        $response = $this->httpPost("openapi/order/v3/calc", $params);
        if (is_error($response)) {
            return error(-1, "查询订单费用失败:" . $response["message"]);
        }
        $response["amount"] = $response["amount"] / 100;
        $order_data_shansong = $this->order["data"]["shansong"];
        if (empty($order_data_shansong)) {
            $order_data_shansong = array();
        }
        $order_data_shansong["fee"] = $response["amount"];
        $id = $this->order["id"];
        set_order_data($id, "shansong", $order_data_shansong);
        return $response;
    }
    public function addOrder()
    {
        if ($this->config["status"] != 1) {
            return error(-1, "闪送功能未开启");
        }
        $params = $this->buildParams();
        $response = $this->httpPost("openapi/order/v3/save", $params);
        if (is_error($response)) {
            return error(-1, "推送订单失败:" . $response["message"]);
        }
        $id = $this->order["id"];
        $order_data_shansong = $this->order["data"]["shansong"];
        if (empty($order_data_shansong)) {
            $order_data_shansong = array();
        }
        $order_data_shansong["status"] = 1;
        $order_data_shansong["issorderno"] = $response;
        set_order_data($id, "shansong", $order_data_shansong);
        return $response;
    }
    public function queryOrderStatus()
    {
        $action = "openapi/order/v3/info";
        $getdata = $this->buildQueryParams();
        $action .= "?" . $getdata;
        $response = $this->httpPost($action, "", "get");
        return $response;
    }
    public function cancelOrder()
    {
        $action = "openapi/order/v3/cancel";
        $getdata = $this->buildQueryParams();
        $action .= "?" . $getdata;
        $response = $this->httpPost($action, "", "get");
        if (is_error($response)) {
            return error(-1, "取消订单失败:" . $response["message"]);
        }
        if (0 < $response["amount"]) {
            $response["amount"] = $response["amount"] / 100;
            $order_data_shansong = $this->order["data"]["shansong"];
            if (empty($order_data_shansong)) {
                $order_data_shansong = array();
            }
            $order_data_shansong["fee"] = $response["amount"];
            $id = $this->order["id"];
            set_order_data($id, "shansong", $order_data_shansong);
        }
        return $response;
    }
}