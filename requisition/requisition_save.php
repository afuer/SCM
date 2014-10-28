<?php

include_once '../lib/DbManager.php';
include './requisition.php';
include './requisition_DTO.php';

$dto = new requisitionDTO();

$mode = getParam('action');
$dto->requisitionId = getParam('search_id');
$dto->productId = getParam('productId');
$dto->price = getParam('price');
$dto->qty = getParam('qty');
$dto->specification = getParam('specification');
$dto->justification = getParam('justification');
$dto->remark = getParam('remark');
$dto->attachTitle = getParam('AttachmentDetails');
$dto->attachPath = getParam('FileName');
$dto->userName = $userName;
$dto->EmployeeId = $employeeId;
$dto->processDeptId = getParam('processDeptId');
$dto->requisitionTypeId = getParam('requisition_type_id');
$dto->OfficeType = $OfficeTypeId;
$dto->BranchDeptId = $BranchDeptId;
$dto->lineManagerId = $lineManagerId;
$dto->helpDesk = getParam('helpDesk');
$dto->freeText = getParam('freeText');



$requisition = new requisition();

if ($mode == 'new') {
    $result = $requisition->save($dto);
} elseif ($mode == 'update') {
    $result = $requisition->update($dto);
} else {
    //$result = $requisition->save($dto);
}
//echo $result->requisitionId;

if ($result) {
    echo json_encode(array('success' => true, 'id' => $dto->requisitionId));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>