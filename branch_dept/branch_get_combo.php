<?php

include_once '../lib/DbManager.php';
include './branch.php';
$div = new branch();


$sql_result = $div->getBranchAll();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

//echo "<pre>";
//print_r($items);

echo json_encode($items);
?>