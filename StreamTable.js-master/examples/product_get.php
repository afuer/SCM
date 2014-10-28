<?php

include '../lib/DbManager.php';
include_once 'JSON.php';
include './product_DTO.php';
$dto = new productDTO();
$className = 'product';




/* Do not Chage below Code */
include "$className.php";
$className = new $className();


$mode = getParam('mode');

if ($mode == 'search') {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 200;
    $offset = ($page - 1) * $rows;

    $dto->productName = getParam('productName');
    $dto->categoryId = getParam('categoryId');
    $dto->categorySubId = getParam('categorySubId');
    $dto->requisitionFor = getParam('requisitionFor');
    //print_r($dto);

    $result = $className->search($offset, $rows, $dto);
} else {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;
    $result = $className->getAll($offset, $rows);
}

echo $result;
?>

