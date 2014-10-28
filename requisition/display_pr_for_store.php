<?php

include '../lib/DbManager.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 200;
$offset = ($page - 1) * $rows;



$result = array();
$q = isset($_POST['q']) ? $_POST['q'] : '';
$requisition_type_id = getParam('requisition_type_id');
$processDept = getParam('processDept');

$res = '';
$res.= $q == '' ? '' : " AND PRODUCT_NAME LIKE '%$q%'";
$res.=$requisition_type_id == '' ? '' : " AND PRODUCT_TYPE_ID='$requisition_type_id'";

$rs = query("select count(*) 
    FROM product p
    left join unit_type pak on pak.UNIT_TYPE_ID = p.UNIT_TYPE_ID
    WHERE 1 $res");

$row = mysql_fetch_row($rs);
$result["total"] = $row[0];




$sql = "SELECT p.PRODUCT_CODE,
p.PRODUCT_ID, 
p.PRODUCT_NAME,
p.REORDER_QTY, 
pak.UNIT_TYPE_NAME

FROM product p
left join unit_type pak on pak.UNIT_TYPE_ID = p.UNIT_TYPE_ID
WHERE PROCESS_DEPT_ID='$processDept' $res
ORDER BY p.PRODUCT_NAME ASC LIMIT $offset,$rows";

$result_sql = query($sql);


$items = array();
while ($row = mysql_fetch_object($result_sql)) {
    array_push($items, $row);
}

$result["rows"] = $items;

echo json_encode($result);
?>