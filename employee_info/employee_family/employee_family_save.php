<?php
include '../lib/DbManager.php';
include '../body/header.php';
include 'employee_DTO.php';

include 'employee_qualification.php';

$employee = new employee_qualification();


$employeeDTO = new employeeDTO();

$employeeId =getParam('employeeId');

$employeeDTO->employeeId = getParam('employeeId');
 $employeeDTO->primaryId = getParam('primaryId');


  
$employeeDTO->qualificationArea = getParam('QUALIFICATION_AREA');
$employeeDTO->qualificationTitle  = getParam('QUALIFICATION_TITLE');
$employeeDTO->institute = getParam('INSTITUTE');
$employeeDTO->result = getParam('RESULT'); 
$employeeDTO->quaStartDate = date("Y-m-d", strtotime(getParam('START_DATE')));
$employeeDTO->quaEndDate = date("Y-m-d", strtotime(getParam('END_DATE')));

$mode = getParam('mode');

$emp = new employee_qualification();



if ($mode =='edit') {  
    $result = $emp->updateMaster($employeeDTO,$user_name);
} 
if ($mode =='new') {  
    $result = $emp->saveMaster($employeeDTO,$user_name);
} 

if ($mode =='remove') {  
    $removePrimaryId = getParam('primaryId');
    $result = $emp->removeMaster($removePrimaryId);
} 

echo" <script>location.replace('index.php?mode=search&employeeId=$employeeId');</script>";

?>

