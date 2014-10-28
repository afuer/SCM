<?php

include '../lib/DbManager.php';

if (isset($_GET['data'])) {
    $ObjRowItem = json_decode($_GET['data']);

    $requisitionId = $ObjRowItem->requisitionId;
    $productId = $ObjRowItem->productId;
    $costCenterId = $ObjRowItem->costCenterId;
    $costCenterAmount = $ObjRowItem->costCenterAmount;
    $solId = $ObjRowItem->solId;
    //$supplierId = $ObjRowItem->supplierId;


    $SqlInsertCcLis = "INSERT INTO requisition_cc_list (REQUISITION_ID, PRODUCT_ID, REQUISITION_CC_ID, CC_PERCENT, SOL_ID, CREATED_BY, CREATED_DATE)
                    VALUES('$requisitionId', '$productId', '$costCenterId', '$costCenterAmount', '$solId', '$employeeId', NOW())";
    $result = sql($SqlInsertCcLis);

    if ($result) {
        $id = insert_id();
        echo json_encode(array('success' => true, 'Id' => $id));
    } else {
        echo json_encode(array('msg' => 'Some errors occured.'));
    }
} else {
    echo 'not GET';
}
?>

