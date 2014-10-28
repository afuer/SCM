<?php

include '../lib/DbManager.php';


$term = getParam('term');
$requisition_type_id = getParam('requisition_type_id');
//$processDeptId = getParam('processDeptId');

$sql = "SELECT c.DESCRIPTION, 
        p.PRODUCT_NAME AS Product, p.PRODUCT_ID,
        CONCAT(p.PRODUCT_CODE,' ',p.PRODUCT_NAME) AS ProductCode,
        ut.UNIT_TYPE_NAME, p.PURCHASE_PRICE,  p.PROCESS_DEPT_ID

        FROM product p
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        WHERE p.PRODUCT_TYPE_ID='$requisition_type_id' AND p.PROCESS_DEPT_ID IN(2,5)
        AND p.PRODUCT_NAME LIKE '%$term%' ORDER BY Product";


$sql_result = query($sql);

$data = array();
while ($row = fetch_object($sql_result)) {
    $data[] = array(
        'label' => $row->ProductCode,
        'value' => $row->Product,
        'productId' => $row->PRODUCT_ID,
        'unit' => $row->UNIT_TYPE_NAME,
        'category' => $row->DESCRIPTION,
        'price' => $row->PURCHASE_PRICE,
        'processDept' => $row->PROCESS_DEPT_ID
    );
}
echo json_encode($data);
flush();
