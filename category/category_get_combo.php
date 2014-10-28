<?php

include_once '../lib/DbManager.php';
include 'category.php';
$div = new category();


$sql_result = $div->getAllCombo();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

echo json_encode($items);
?>