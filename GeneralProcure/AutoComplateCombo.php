<?php

include 'include.php';
$ProductName = getParam('term');
//$ProductList = rs2array(query("SELECT product_id, PRODUCT_NAME FROM product ORDER BY product_id"));
$ProductList = query("SELECT product_id, PRODUCT_NAME, PRODUCT_CODE FROM product WHERE  PRODUCT_NAME LIKE '%$ProductName%' ORDER BY PRODUCT_NAME");

$results = array();
while ($row = mysql_fetch_object($ProductList)) {
    $results[] = array('ProductName' => $row->PRODUCT_NAME, 'ProductCode' => $row->PRODUCT_CODE);
    //array_push($results, $row->PRODUCT_NAME);
}
//$product_list = substr($product_list, 0, -1);
//echo $product_list = substr($product_list, 0, -1);
echo json_encode($results);
?>


