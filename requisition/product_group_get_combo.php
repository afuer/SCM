<?php

include_once '../lib/DbManager.php';
include 'product.php';
$product = new product();


$sql_result = $product->getProductGroupAll();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

//echo "<pre>";
//print_r($items);

echo json_encode($items);
?>