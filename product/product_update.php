<?php

include_once '../lib/DbManager.php';
include 'product_DTO.php';
include_once './product.php';



$productDTO = new product_DTO();
$productDTO->productId = getParam('productId');
$productDTO->productCode = getParam('PRODUCT_CODE');
$productDTO->productName = getParam('PRODUCT_NAME');
$productDTO->descriotion = getParam('DESCRIPTION');
$productDTO->mCode = getParam('MCODE');
$productDTO->purchasePrice = getParam('PURCHASE_PRICE');
$productDTO->qty = getParam('QTY');
$productDTO->categotyId = getParam('CATEGORY_ID');
$productDTO->subCategoryId = getParam('SUB_CATEGORY_ID');
$productDTO->productGroupId = getParam('PRODUCT_GROUP_ID');
$productDTO->requisitionRouteId = getParam('REQUISITION_ROUTE_ID');
$productDTO->packSizeId = getParam('PACKSIZE_ID');
$productDTO->unitTypeId = getParam('UNIT_TYPE_ID');
$productDTO->isActive = getParam('ISACTIVE');
$productDTO->reorderLevel = getParam('REORDER_LEVEL');
$productDTO->dailyExpense = getParam('DAILY_EXPENSE');
$productDTO->leadTime = getParam('LEAD_TIME');
$productDTO->reorderQty = getParam('REORDER_QTY');
$productDTO->oscommerceId = getParam('OSCOMMERCEID');
$productDTO->free = getParam('FREE');
$productDTO->productTypeId = getParam('PRODUCT_TYPE_ID');
$productDTO->atActual = getParam('AT_ACTUAL');
$productDTO->requisitionFor = getParam('REQUISITION_FOR');
$productDTO->modifyBy = "$user_name";

$product = new product();

$result = $product->Update($productDTO);

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>