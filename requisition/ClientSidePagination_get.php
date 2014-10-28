<?php

include '../lib/DbManager.php';
include './ClientSidePaginationRequisition.php';;



$requisition = new ClientSidePagination();

 $mode = getParam('mode');

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
$offset = ($page - 1) * $rows;

//print_r($dto);


$result = $requisition->getAll($offset, $rows);

echo $result;
?>

