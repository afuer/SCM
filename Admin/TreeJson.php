<?php

include 'include.php';
$menu_id = getParam('id');
$res = $menu_id == '' ? " AND SYS_MENU_ID='500'" : " AND SYS_MENU_ID='$menu_id'";
$result = array();
$rs = mysql_query("SELECT SYS_MENU_ID, MENU_NAME FROM sys_menu WHERE _GROUP='main' AND _SHOW='1' $res");
while ($row = mysql_fetch_object($rs)) {
    $node = array();
    $node['id'] = $row->SYS_MENU_ID;
    $node['text'] = $row->MENU_NAME;
    $node['state'] = 'open';
    $node['children'] = has_child($row->SYS_MENU_ID);
    array_push($result, $node);
}

echo json_encode($result);

function has_child($id) {
    $nodes = array();
    $rs = mysql_query("SELECT SYS_MENU_ID, MENU_NAME FROM sys_menu WHERE _GROUP='sub' AND _SHOW='1' AND _SUBID='$id'");
    while ($row_sub = mysql_fetch_array($rs)) {
        $nodes['id'] = $row_sub['SYS_MENU_ID'];
        $nodes['text'] = $row_sub['MENU_NAME'];
    }
    return $nodes;
}

//json_encode(has_child(504));
?>