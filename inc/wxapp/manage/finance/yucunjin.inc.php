
<?php  defined("IN_IA") or exit( "Access Denied" );
global $_W;
global $_GPC;
$ta = (trim($_GPC["ta"]) ? trim($_GPC["ta"]) : "list");
if( $ta == "list" ) 
{
	$condition = " where uniacid = :uniacid and sid = :sid";
	$params = array( ":uniacid" => $_W["uniacid"], ":sid" => $sid );
	$trade_type = intval($_GPC["trade_type"]);
	if( 0 < $trade_type ) 
	{
		$condition .= " and trade_type = :trade_type";
		$params[":trade_type"] = $trade_type;
	}
	$page = max(1, intval($_GPC["page"]));
	$psize = (intval($_GPC["psize"]) ? intval($_GPC["psize"]) : 15);
	$records = pdo_fetchall("select * from " . tablename("tiny_wmall_store_yucunjin_log") . $condition . " order by id desc limit " . ($page - 1) * $psize . ", " . $psize, $params);
	if( !empty($records) ) 
	{
		$trade_types = store_yucunjin_type();
		foreach( $records as &$row ) 
		{
			$row["trade_type_cn"] = $trade_types[$row["trade_type"]]["text"];
			$row["addtime_cn"] = date("Y-m-d H:i", $row["addtime"]);
		}
	}
	$result = array( "records" => $records );
	imessage(error(0, $result), "", "ajax");
}
?>