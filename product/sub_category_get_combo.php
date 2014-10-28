<?php
include_once '../lib/DbManager.php';
include 'category.php';
$category = new Category();

$val=  getParam('val');

$sql_result = $category->getSubCategoryAll($val);
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

echo json_encode($items);
?>