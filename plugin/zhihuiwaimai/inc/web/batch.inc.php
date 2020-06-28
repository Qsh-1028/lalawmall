<?php
/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;

$_W['page']['title'] = '开始采集';
$op = trim($_GPC['op']) ? trim($_GPC['op']): 'batch';
set_time_limit(0);
load()->func('communication');

if($op == 'store_category') {
	$cache_store_category = cache_read("zhihuiwaimai:store_category:{$_W['uniacid']}");
	$store_categorys = pdo_fetchall('select * from ' . tablename('cjdc_storetype'), array(), 'id');
	if(!empty($store_categorys)) {
		foreach($store_categorys as $key => $value) {
			if(!empty($cache_store_category) && in_array($value['id'], array_keys($cache_store_category))){
				continue;
			}
			$insert = array(
				'uniacid' => $value['uniacid'],
				'parentid' => 0,
				'title' => trim($value['type_name']),
				'thumb' => trim($value['img']),
				'status' => 1,
				'displayorder' => $value['num'],
			);
			pdo_insert('tiny_wmall_store_category', $insert);
			$insert_cid = pdo_insertid();
			$cache_store_category[$value['id']] = $insert_cid;
		}
		cache_write("zhihuiwaimai:store_category:{$_W['uniacid']}", $cache_store_category);
	}
	imessage("商户分类拉取成功，正在拉取第1页商户数据", iurl('zhihuiwaimai/batch/store', array('page' => 1)), 'success');
}

elseif($op == 'store') {
	$cache_store_category = cache_read("zhihuiwaimai:store_category:{$_W['uniacid']}");
	$cache_store = cache_read("zhihuiwaimai:store:{$_W['uniacid']}");

	$page = intval($_GPC['page']) > 0 ? intval($_GPC['page']) : 1;
	$psize = 1000;
	$stores = pdo_fetchall('select * from ' . tablename('cjdc_store') . ' order by id asc limit '.($page - 1) * $psize.','.$psize, array(), 'id');
	if(!empty($stores)) {
		foreach($stores as $key => $value) {
			if(!empty($cache_store) && in_array($value['id'], array_keys($cache_store))){
				continue;
			}
			//经纬度
			$coordinates = explode(',',$value['coordinates']);
			//营业资质
			$yyzz = explode(',',$value['yyzz']);
			//门店实景
			$environment = explode(',',$value['environment']);
			$thumbs = array();
			foreach($environment as $env) {
				$thumbs['image'][] = $env;
			}
			//营业时间
			$business_hours = array(
				'0' => array(
					's' => $value['time'],
					'e' => $value['time2']
				),
				'1' => array(
					's' => $value['time3'],
					'e' => $value['time4']
				),
			);

			$insert = array(
				'uniacid' => $value['uniacid'],
				'agentid' => 0,
				'cid' => "|{$cache_store_category[$value['md_type']]}|",
				'logo' => trim($value['logo']),
				'title' => trim($value['name']),
				'telephone' => $value['tel'],
				'address' => trim($value['address']),
				'cate_parentid1' => $cache_store_category[$value['md_type']],
				'location_x' => $coordinates[0],
				'location_y' => $coordinates[1],
				'consume_per_person' => floatval($value['capita']),
				'description' => trim($value['details']),
				'thumbs' => iserializer($thumbs),
				'qualification' => iserializer(array(
					'business' => array(
						'thumb' => trim($yyzz[0]),
					),
					'more1' => array(
						'thumb' => trim($yyzz[1]),
					),
					'more2' => array(
						'thumb' => trim($yyzz[2]),
					)
				)),
				'sailed' => intval($value['score']),
				'business_hours' => iserializer($business_hours)
			);

			pdo_insert('tiny_wmall_store', $insert);
			$sid = pdo_insertid();
			$cache_store[$value['id']] = $sid;

			//添加门店账户数据
			$config_serve_fee = $_W['we7_wmall']['config']['store']['serve_fee'];
			$store_account = array(
				'uniacid' => $value['uniacid'],
				'sid' => $sid,
				'fee_takeout' => iserializer($config_serve_fee['fee_takeout']),
				'fee_selfDelivery' => iserializer($config_serve_fee['fee_selfDelivery']),
				'fee_instore' => iserializer($config_serve_fee['fee_instore']),
				'fee_paybill' => iserializer($config_serve_fee['fee_paybill']),
				'fee_limit' => $config_serve_fee['get_cash_fee_limit'],
				'fee_rate' => $config_serve_fee['get_cash_fee_rate'],
				'fee_min' => $config_serve_fee['get_cash_fee_min'],
				'fee_max' => $config_serve_fee['get_cash_fee_max'],
			);
			pdo_insert('tiny_wmall_store_account', $store_account);
		}
	}
	cache_write("zhihuiwaimai:store:{$_W['uniacid']}", $cache_store);
	if(count($stores) == 1000){
		$page++;
		imessage("正在拉取第{$page}页商户数据,请勿关闭浏览器", iurl('zhihuiwaimai/batch/store', array('page' => $page)), 'success');
	} else {
		imessage("商户数据拉取成功，正在拉取第2页商品分类数据", iurl('zhihuiwaimai/batch/goods_category', array('page' => 1)), 'success');
	}
}

elseif($op == 'goods_category') {
	$cache_store = cache_read("zhihuiwaimai:store:{$_W['uniacid']}");
	$cache_goods_category = cache_read("zhihuiwaimai:goods_category:{$_W['uniacid']}");

	$page = intval($_GPC['page']) > 0 ? intval($_GPC['page']) : 1;
	$psize = 1000;
	$goods_categorys = pdo_fetchall('select * from ' . tablename('cjdc_type') . ' limit '.($page - 1) * $psize.','.$psize);
	if(!empty($goods_categorys)) {
		foreach($goods_categorys as $key => $value) {
			if(!empty($cache_goods_category) && in_array($value['id'], array_keys($cache_goods_category))){
				continue;
			}
			$insert = array(
				'uniacid' => $value['uniacid'],
				'sid' => $cache_store[$value['store_id']],
				'parentid' => 0,
				'title' => trim($value['type_name']),
				'status' => $value['is_open'] == 1 ? 1 : 0,
				'displayorder' => $value['order_by'],
			);
			pdo_insert('tiny_wmall_goods_category', $insert);
			$goods_category_id = pdo_insertid();
			$cache_goods_category[$value['id']] = $goods_category_id;
		}
	}
	cache_write("zhihuiwaimai:goods_category:{$_W['uniacid']}", $cache_goods_category);
	if(count($goods_categorys) == 1000){
		$page++;
		imessage("正在拉取第{$page}页商品分类数据,请勿关闭浏览器", iurl('zhihuiwaimai/batch/goods_category', array('page' => $page)), 'success');
	} else {
		imessage("商品分类数据拉取成功，正在拉取第1页商品数据", iurl('zhihuiwaimai/batch/goods', array('page' => 1)), 'success');
	}
}

elseif($op == 'goods') {
	$cache_store = cache_read("zhihuiwaimai:store:{$_W['uniacid']}");
	$cache_goods_category = cache_read("zhihuiwaimai:goods_category:{$_W['uniacid']}");

	$page = intval($_GPC['page']) > 0 ? intval($_GPC['page']) : 1;
	$psize = 1000;
	$goods = pdo_fetchall('select * from ' . tablename('cjdc_goods') . ' limit '.($page - 1) * $psize.','.$psize);
	if(!empty($goods)) {
		foreach($goods as $key => $value) {
			/*淘 宝 柠 檬 鱼 科 技 https://shop486845690.taobao.com*/
			$insert = array(
				'uniacid' => $value['uniacid'],
				'sid' => $cache_store[$value['store_id']],
				'cid' => $cache_goods_category[$value['type_id']],
				'child_id' => 0,
				'title' => $value['name'],
				'thumb' => trim($value['logo']),
				'price' => floatval($value['money']),
				'old_price' => floatval($value['money2']),
				'svip_price' => floatval($value['vip_money']),
				'ts_price' => floatval($value['dn_money']),
				'box_price' => floatval($value['box_money']),
				'is_options' => $value['is_spec'],
				'unitname' => $value['unit'],
				'total' => $value['is_max'] == 1 ? -1 : intval($value['inventory']),
				'status' => $value['is_show'] == 1 ? 1 : 0,
				'is_hot' => $value['is_hot'],
				'type' => intval($value['type']),
				'displayorder' => $value['num'],
				'content' => trim($value['content']),
				'description' => $value['details'],
				'sailed' => intval($value['sails']),
				'unitnum' => intval($value['start_num'])
			);

			pdo_insert('tiny_wmall_goods', $insert);
			$goods_id = pdo_insertid();
		}
	}
	if(count($goods) == 1000) {
		$page++;
		imessage("正在拉取第{$page}页商品数据,请勿关闭浏览器", iurl('zhihuiwaimai/batch/goods', array('page' => $page)), 'success');
	} else {
		imessage("商品数据拉取成功", iurl('zhihuiwaimai/batch/index', array()), 'success');
	}
}

elseif($op == 'afresh') {
	cache_clean("zhihuiwaimai");
	$table = array(
		'tiny_wmall_goods', 'tiny_wmall_goods_category', 'tiny_wmall_store', 'tiny_wmall_store_account', 'tiny_wmall_store_category');
	foreach ($table as $value) {
		pdo_delete($value, array('uniacid' => $_W['uniacid']));
		//pdo_run('truncate ' . tablename($value));
	}
	imessage("数据清空成功,即将重新采集,请勿关闭浏览器", iurl('zhihuiwaimai/batch/store_category'), 'success');
}

include itemplate('batch');