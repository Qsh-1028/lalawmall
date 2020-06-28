
<?php  defined("IN_IA") or exit( "Access Denied" );
global $_W;
global $_GPC;
$ta = (trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "log");
if( $ta == "log" ) 
{
	$_W["page"]["title"] = "商户预存金明细";
	$condition = " WHERE uniacid = :uniacid and sid = :sid ";
	$params = array( ":uniacid" => $_W["uniacid"], ":sid" => $sid );
	$trade_type = intval($_GPC["trade_type"]);
	if( 0 < $trade_type ) 
	{
		$condition .= " AND trade_type = :trade_type";
		$params[":trade_type"] = $trade_type;
	}
	$days = (isset($_GPC["days"]) ? intval($_GPC["days"]) : -2);
	$todaytime = strtotime(date("Y-m-d"));
	$starttime = $todaytime;
	$endtime = $starttime + 86399;
	if( -2 < $days ) 
	{
		if( $days == -1 ) 
		{
			$starttime = strtotime($_GPC["addtime"]["start"]);
			$endtime = strtotime($_GPC["addtime"]["end"]);
			$condition .= " AND addtime > :start AND addtime < :end";
			$params[":start"] = $starttime;
			$params[":end"] = $endtime;
		}
		else 
		{
			$starttime = strtotime("-" . $days . " days", $todaytime);
			$condition .= " and addtime >= :start";
			$params[":start"] = $starttime;
		}
	}
	$pindex = max(1, intval($_GPC["page"]));
	$psize = 15;
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_store_yucunjin_log") . $condition, $params);
	$records = pdo_fetchall("SELECT * FROM " . tablename("tiny_wmall_store_yucunjin_log") . $condition . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
	$types = store_yucunjin_type();
	$pager = pagination($total, $pindex, $psize);
	$stores = pdo_getall("tiny_wmall_store", array( "uniacid" => $_W["uniacid"] ), array( "id", "title", "logo" ), "id");
}
include(itemplate("store/finance/yucunjin"));
?>