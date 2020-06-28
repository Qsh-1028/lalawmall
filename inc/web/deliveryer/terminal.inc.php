
<?php  defined("IN_IA") or exit( "Access Denied" );
global $_W;
global $_GPC;
$op = (trim($_GPC["op"]) ? trim($_GPC["op"]) : "list");
if( $op == "post" ) 
{
	$_W["page"]["title"] = "编辑配送站";
	$id = intval($_GPC["id"]);
	if( 0 < $id ) 
	{
		$terminal = pdo_get("tiny_wmall_deliveryer_terminal", array( "uniacid" => $_W["uniacid"], "id" => $id ));
		if( empty($terminal) ) 
		{
			imessage("广告不存在或已删除", referer(), "error");
		}
	}
	if( $_W["ispost"] ) 
	{
		$title = (trim($_GPC["title"]) ? trim($_GPC["title"]) : imessage(error(-1, "标题不能为空"), "", "ajax"));
		$data = array( "uniacid" => $_W["uniacid"], "title" => $title, "content" => trim($_GPC["content"]), "status" => intval($_GPC["status"]), "displayorder" => intval($_GPC["displayorder"]) );
		if( !empty($terminal) ) 
		{
			pdo_update("tiny_wmall_deliveryer_terminal", $data, array( "uniacid" => $_W["uniacid"], "id" => $id ));
		}
		else 
		{
			pdo_insert("tiny_wmall_deliveryer_terminal", $data);
		}
		imessage(error(0, "编辑配送站成功"), iurl("deliveryer/terminal/list"), "ajax");
	}
	include(itemplate("deliveryer/terminal"));
}
if( $op == "list" ) 
{
	$_W["page"]["title"] = "配送站列表";
	if( $_W["ispost"] ) 
	{
		if( !empty($_GPC["ids"]) ) 
		{
			foreach( $_GPC["ids"] as $k => $v ) 
			{
				$data = array( "title" => trim($_GPC["titles"][$k]), "displayorder" => intval($_GPC["displayorders"][$k]) );
				pdo_update("tiny_wmall_deliveryer_terminal", $data, array( "uniacid" => $_W["uniacid"], "id" => intval($v) ));
			}
		}
		imessage(error(0, "编辑配送站成功"), iurl("deliveryer/terminal/list"), "success");
	}
	$condition = " where uniacid = :uniacid";
	$params = array( ":uniacid" => $_W["uniacid"] );
	$agentid = intval($_GPC["agentid"]);
	if( 0 < $agentid ) 
	{
		$condition .= " and agentid = :agentid";
		$params[":agentid"] = $agentid;
	}
	$pindex = max(1, intval($_GPC["page"]));
	$psize = 15;
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("tiny_wmall_deliveryer_terminal") . $condition, $params);
	$terminals = pdo_fetchall("select * from" . tablename("tiny_wmall_deliveryer_terminal") . $condition . " order by displayorder desc limit " . ($pindex - 1) * $psize . "," . $psize, $params);
	$pager = pagination($total, $pindex, $psize);
	include(itemplate("deliveryer/terminal"));
}
if( $op == "del" ) 
{
	$ids = $_GPC["id"];
	if( !is_array($ids) ) 
	{
		$ids = array( $ids );
	}
	foreach( $ids as $id ) 
	{
		pdo_delete("tiny_wmall_deliveryer_terminal", array( "uniacid" => $_W["uniacid"], "id" => $id ));
	}
	imessage(error(0, "删除配送站成功"), referer(), "ajax");
}
if( $op == "status" ) 
{
	$id = intval($_GPC["id"]);
	$status = intval($_GPC["status"]);
	pdo_update("tiny_wmall_deliveryer_terminal", array( "status" => $status ), array( "uniacid" => $_W["uniacid"], "id" => $id ));
	imessage(error(0, ""), "", "ajax");
}
if( $op == "displayorder" ) 
{
	$id = intval($_GPC["id"]);
	$displayorder = intval($_GPC["displayorder"]);
	pdo_update("tiny_wmall_deliveryer_terminal", array( "displayorder" => $displayorder ), array( "uniacid" => $_W["uniacid"], "id" => $id ));
	imessage(error(0, ""), "", "ajax");
}
if( $op == "terminalagent" ) 
{
	if( $_W["is_agent"] ) 
	{
		$agents = get_agents();
	}
	$ids = $_GPC["id"];
	$ids = implode(",", $ids);
	if( $_W["ispost"] && $_GPC["set"] == 1 ) 
	{
		$terminalid = explode(",", $_GPC["id"]);
		$agentid = intval($_GPC["agentid"]);
		if( 0 < $agentid ) 
		{
			foreach( $terminalid as $value ) 
			{
				pdo_update("tiny_wmall_deliveryer_terminal", array( "agentid" => $agentid ), array( "uniacid" => $_W["uniacid"], "type" => "startpage", "id" => $value ));
			}
		}
		imessage(error(0, "批量操作修改成功"), iurl("deliveryer/terminal/list"), "ajax");
	}
	include(itemplate("deliveryer/op"));
}
?>