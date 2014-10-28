<?php

include '../lib/DbManager.php';
include './requisition.php';

$requisition = new requisition();

$search_id = getParam('search_id');

$result = $requisition->product_list($search_id);
echo $result;
?>

