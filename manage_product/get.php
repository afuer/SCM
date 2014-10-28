<?php

include_once '../lib/DbManager.php';
include_once './manage_product.php';
$ManageProduct = new ManageProduct();


$mode = getParam('mode');
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;

if ($mode == 'pending') {
    $result = $ManageProduct->getPendingAll($ProcessDeptId, $offset, $rows);
} else {
    $result = $ManageProduct->getAll($ProcessDeptId, $offset, $rows);
}



echo $result;



?>
