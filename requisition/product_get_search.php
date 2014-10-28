<?php

include '../lib/DbManager.php';
$db = new DbManager();
$db->OpenDb();

$ProductName = getParam('term');

//$ProductList = rs2array(query("SELECT product_id, PRODUCT_NAME FROM product ORDER BY product_id"));
$ProductList = query("SELECT product_id, PRODUCT_NAME, PRODUCT_CODE FROM product WHERE  PRODUCT_NAME LIKE '%$ProductName%' ORDER BY PRODUCT_NAME");
$db->CloseDb();

$results = array();
while ($row = mysql_fetch_object($ProductList)) {
    $results[] = array('ProductName' => $row->PRODUCT_NAME, 'ProductCode' => $row->PRODUCT_CODE);
}

echo json_encode($results);
?>


