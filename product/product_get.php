<?php

include '../lib/DbManager.php';
include './product_DTO.php';
$dto = new productDTO();


/* Do not Chage below Code */
include "product.php";
$product = new product();


$mode = getParam('mode');

if ($mode == 'search') {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 200;
    $offset = ($page - 1) * $rows;

    $dto->ProductName = getParam('ProductName');
    $dto->CategoryId = getParam('CategoryId');
    $dto->CategorySubId = getParam('CategorySubId');
    $dto->CategoryUnderSubId = getParam('CategoryUnderSubId');
    $dto->ProcessDeptId = getParam('ProcessDeptId');
    $dto->ProductTypeId = getParam('ProductTypeId');
    $dto->ProductGroup = getParam('ProductGroup');
    //print_r($dto);

    $result = $product->search($offset, $rows, $dto);
} else {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;
    $result = $product->getAll($offset, $rows);
}

echo $result;
?>

