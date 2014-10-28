<?php

include '../lib/DbManager.php';

if (isset($_GET['data'])) {
    $ObjRowItem = json_decode($_GET['data']);

    $Workflow = $ObjRowItem->Workflow;
    $CardNo = $ObjRowItem->CardNo;
    $Designation = $ObjRowItem->Designation;
    $RequisitionId = $ObjRowItem->RequisitionId;
    print_r($ObjRowItem);

    $SL = findValue("SELECT IFNULL(MAX(SL),0)+1 FROM requisition_flow_list WHERE REQUISITION_ID='$RequisitionId' ");

    $MaxRequisitionFlowListId = NextId('requisition_flow_list', 'GP_REQUISITION_FLOW_LIST_ID');
    $SqlInsertWd = "INSERT INTO requisition_flow_list (GP_REQUISITION_FLOW_LIST_ID, REQUISITION_ID, EMPLOYEE_ID, DESIGNATION_ID, WORKFLOW_PROCESS_TYPE_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE)
            VALUES('$MaxRequisitionFlowListId', '$RequisitionId', '$CardNo', '$Designation', '$Workflow', '$SL', '0', '$user_name', NOW())";
    sql($SqlInsertWd);



} else {
    echo 'not GET';
}
?>

