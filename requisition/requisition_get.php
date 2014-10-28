<?php

include '../lib/DbManager.php';
include './requisition.php';
include './requisition_DTO.php';




$requisition = new requisition();
$dto = new requisitionDTO();

$mode = getParam('mode');

if ($mode == 'search') {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;

    $dto->requisitionNo = getParam('ReqNo');
    $dto->ReqStatus = getParam('ReqStatus');
    $dto->requisitionTypeId = getParam('ReqType');
    $dto->processDeptId=  getParam('ProcessDeptId');
    $dto->FromDate = getParam('FromDate');
    $dto->ToDate = getParam('ToDate');
    //print_r($dto);

    $result = $requisition->search($offset, $rows, $dto, $employeeId);
} elseif ($mode == 'all') {

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;
    $result = $requisition->getAll($offset, $rows, $employeeId);
} else {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;
    $result = $requisition->RequisitionApproval($offset, $rows, $employeeId, $UserLevelId);
}

echo $result;
?>

