<?php
include 'conn.php';

$result = array();
$rs = mysql_query("SELECT id, `NAME` FROM sys_menu WHERE _group='main' AND _show='1'");
while($row = mysql_fetch_array($rs)){
	$node = array();
	$node['id'] = $row['id'];
	$node['text'] = $row['NAME'];
	$node['state'] = has_child($row['id']) ? 'closed' : 'open';
	array_push($result,$node);
}

echo json_encode($result);

function has_child($id){
	$rs = mysql_query("SELECT id, `NAME` FROM sys_menu WHERE _group='sub' AND _show='1' AND _subid='$id'");
	$row = mysql_fetch_array($rs);
	return $row[0] > 0 ? true : false;
}

?>