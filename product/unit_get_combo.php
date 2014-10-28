<?php
include_once '../lib/DbManager.php';
include './product.php';
$product = new product();

$val=  getParam('val');

$sql_result = $product->getUnitAll();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

echo json_encode($items);
?>