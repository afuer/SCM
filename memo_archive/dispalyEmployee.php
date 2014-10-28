<?php

include '../lib/DbManager.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 200;
$offset = ($page - 1) * $rows;

$db = new DbManager();

$result = array();
$q = isset($_POST['q']) ? $_POST['q'] : '';
//$requisition_type_id = getParam('requisition_type_id');

$db->OpenDb();
$rs = query("select count(*) from product");
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];

$res = $q == '' ? '' : " AND PRODUCT_NAME LIKE '%$q%'";
/*
$sql = "SELECT p.PRODUCT_CODE,
p.PRODUCT_ID, 
p.PRODUCT_NAME,
p.REORDER_QTY, 
pak.UNIT_TYPE_NAME

FROM product p
left join unit_type pak on pak.UNIT_TYPE_ID = p.UNIT_TYPE_ID

ORDER BY p.PRODUCT_NAME ASC LIMIT $offset,$rows";
*/

$sql ="SELECT
	EMPLOYEE_ID,
	CARD_NO,
	FIRST_NAME,
	CONCAT(CARD_NO, '-', FIRST_NAME)AS EM
        FROM
	employee
        LIMIT $offset,$rows";

$result_sql = query($sql);
$db->CloseDb();

$items = array();
while ($row = mysql_fetch_object($result_sql)) {
    array_push($items, $row);
}

$result["rows"] = $items;

echo json_encode($result);
?>
