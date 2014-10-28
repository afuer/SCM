<?php

include_once '../lib/DbManager.php';
include './division.php';
$div = new division();


$sql_result = $div->getDivisionAll();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

//echo "<pre>";
//print_r($items);

echo json_encode($items);
?>