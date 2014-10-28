<?php

include_once '../lib/DbManager.php';
include '../product/product.php';
$product = new product();


$sql_result = $product->getProductAll($ProcessDeptId);
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

echo json_encode($items);
?>