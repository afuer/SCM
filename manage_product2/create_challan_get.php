<?php

include_once '../lib/DbManager.php';
include_once './manage_product.php';
$ManageProduct = new ManageProduct();


$mode = getParam('mode');
$BranchDeptId = getParam('BranchDeptId');

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;

if ($mode == 'branch') {
    $result = branchGet($ProcessDeptId, $BranchDeptId);
} else {
    //$result = getAll($ProcessDeptId, $offset, $rows);
}



echo $result;

function branchGet($ProcessDeptId, $BranchDeptId) {
    $result = array();
    $result["total"] = branchCount();


    $sql = "SELECT  p.PRODUCT_CODE, p.PRODUCT_NAME, sum(apdh.delivery_qty) AS del_qty,
                            so.REQUISITION_NO, p.PRODUCT_ID, apdh.req_id, so.REQUISITION_ID, so.BRANCH_DEPT_ID
                            FROM product AS p
                            LEFT JOIN app_product_delivery_history AS apdh ON apdh.product_id=p.PRODUCT_ID
                            LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                            WHERE so.OFFICE_TYPE_ID='2' AND apdh.challan_id IS NULL AND p.PRODUCT_TYPE_ID='1'
                            AND so.PROCESS_DEPT_ID='$ProcessDeptId' AND BRANCH_DEPT_ID='$BranchDeptId'
                            GROUP BY apdh.req_id, p.PRODUCT_ID";
    $sql_result = $this->query($sql);

    $items = array();
    while ($row = fetch_object($sql_result)) {
        array_push($items, $row);
    }
    $result["rows"] = $items;

    return json_encode($result);
}

function branchCount($ProcessDeptId, $BranchDeptId) {


    $sql = "SELECT count(*) 
            FROM product AS p
                            LEFT JOIN app_product_delivery_history AS apdh ON apdh.product_id=p.PRODUCT_ID
                            LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                            WHERE so.OFFICE_TYPE_ID='2' AND apdh.challan_id IS NULL AND p.PRODUCT_TYPE_ID='1'
                            AND so.PROCESS_DEPT_ID='$ProcessDeptId' AND BRANCH_DEPT_ID='$BranchDeptId'
                            GROUP BY apdh.req_id, p.PRODUCT_ID";

    $rs = $this->query($sql);
    $row = fetch_row($rs);


    return $row[0];
}

?>
