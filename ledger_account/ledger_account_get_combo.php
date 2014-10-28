<?php

include_once '../lib/DbManager.php';
include 'ledger_account.php';
$div = new ledger_account();


$sql_result = $div->getAllCombo();
$items = array();
while ($row = mysql_fetch_object($sql_result)) {
    array_push($items, $row);
}

//echo "<pre>";
//print_r($items);

echo json_encode($items);
?>