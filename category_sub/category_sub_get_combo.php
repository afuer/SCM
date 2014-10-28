<?php

include_once '../lib/DbManager.php';
include './category_sub.php';
$sub = new category_sub();

$categoryId = getParam('val');

$sql_result = $sub->getFilterCombo($categoryId);
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

//echo "<pre>";
//print_r($items);

echo json_encode($items);
?>