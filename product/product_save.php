<?php

include_once '../lib/DbManager.php';

$Mode = getParam('mode');
$productId = getParam('search_id');
$productCode = getParam('PRODUCT_CODE');
$ProductName = getParam('PRODUCT_NAME');
$ProductDescription = getParam('DESCRIPTION');
$categoryId = getParam('CATEGORY_ID');
$CategorySubId = getParam('CATEGORY_SUB_ID');
$CategorySubUnderId = getParam('UNDER_SUB_CATEGORY_ID');
$ProductBrand = getParam('PRODUCT_BRAND_ID');
$processDeptId = getParam('PROCESS_DEPT_ID');
$UnitTypeId = getParam('UNIT_TYPE_ID');
$ReorderLevel = getParam('REORDER_LEVEL');
$DailyExpense = getParam('DAILY_EXPENSE');
$LeadTime = getParam('LEAD_TIME');
$LastPurchase = getParam('PURCHASE_PRICE');
$ProductGroupId = getParam('PRODUCT_GROUP_ID');
$AtActual = getParam('AT_ACTUAL');
$ProductTypeId = getParam('PRODUCT_TYPE_ID');
$IsActive = getParam('ISACTIVE');



if ($Mode == 'new') {

    $MaxProductId = NextId('product', 'PRODUCT_ID');
    //$productCode = $categoryId . $CategorySubId . $MaxProductId;

    $sql = "INSERT INTO product( PRODUCT_ID, PRODUCT_CODE, PRODUCT_NAME, DESCRIPTION, CATEGORY_ID, CATEGORY_SUB_ID, UNDER_SUB_CATEGORY_ID, PRODUCT_BRAND_ID, PROCESS_DEPT_ID, UNIT_TYPE_ID, REORDER_LEVEL, DAILY_EXPENSE, LEAD_TIME, PURCHASE_PRICE, PRODUCT_GROUP_ID, AT_ACTUAL, PRODUCT_TYPE_ID, ISACTIVE) 
    VALUES('$MaxProductId', '$productCode', '$ProductName', '$ProductDescription', '$categoryId', '$CategorySubId', '$CategorySubUnderId', '$ProductBrand', '$processDeptId', '$UnitTypeId', '$ReorderLevel', '$DailyExpense', '$LeadTime', '$LastPurchase', '$ProductGroupId', '$AtActual', '$ProductTypeId', '$IsActive')";

    $result = sql($sql);
} elseif ($Mode == 'delete') {
    $sql = "delete FROM product where PRODUCT_ID='$productId'";

    $result = sql($sql);
} else {

    $sql = "UPDATE product SET 
        PRODUCT_NAME='$ProductName', 
        DESCRIPTION='$ProductDescription', 
        CATEGORY_ID='$categoryId', 
        CATEGORY_SUB_ID='$CategorySubId', 
        UNDER_SUB_CATEGORY_ID='$CategorySubUnderId',
        PRODUCT_BRAND_ID='$ProductBrand', 
        PROCESS_DEPT_ID='$processDeptId', 
        UNIT_TYPE_ID='$UnitTypeId', 
        REORDER_LEVEL='$ReorderLevel', 
        DAILY_EXPENSE='$DailyExpense', 
        LEAD_TIME='$LeadTime', 
        PURCHASE_PRICE='$LastPurchase', 
        PRODUCT_GROUP_ID='$ProductGroupId', 
        AT_ACTUAL='$AtActual', 
        PRODUCT_TYPE_ID='$ProductTypeId', 
        ISACTIVE='$IsActive'
        WHERE PRODUCT_ID='$productId'";

    $result = sql($sql);
}



if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>