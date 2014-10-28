<?php

include_once '../lib/DbManager.php';
include 'product.php';

$product = new product();



$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;



$result = array();


$result["total"] = $product->Count();

$sql_result = $product->getAll($offset, $rows);

$items = array();
while ($row = fetch_object($sql_result)) {
    array_push($items, $row);
}

//mysql_free_result($sql_result);

$result["rows"] = $items;

echo json_encode($result);
?>