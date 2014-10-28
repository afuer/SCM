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

$sql = "SELECT CONCAT(e.FIRST_NAME,' ',e.LAST_NAME, '->',e.CARD_NO, '(', d.DESIGNATION_NAME, ')') AS empInfo,
    e.EMPLOYEE_ID, e.DESIGNATION_ID, CONCAT(e.FIRST_NAME,' ',e.LAST_NAME, '->',e.CARD_NO) AS empName
    FROM employee e
    LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
    WHERE e.FIRST_NAME LIKE '%$term%' OR e.LAST_NAME LIKE '%$term%' OR e.CARD_NO LIKE '%$term%'
    ORDER BY e.CARD_NO";


$sql_result = query($sql);

$data = array();
while ($row = fetch_object($sql_result)) {
    $data[] = array(
        'label' => $row->empInfo,
        'value' => $row->empName,
        'employeeId' => $row->EMPLOYEE_ID,
        'designationId' => $row->DESIGNATION_ID,
        'category' => $row->DESCRIPTION
    );
}
echo json_encode($data);
flush();
