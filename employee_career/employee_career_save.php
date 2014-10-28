<?php
include '../lib/DbManager.php';
include '../body/header.php';
include 'employee_DTO.php';

include 'employee_career.php';

$employee = new employee_career();


$employeeDTO = new employeeDTO();

$employeeId =getParam('employeeId');

$employeeDTO->employeeId = getParam('employeeId');
$employeeDTO->careerId = getParam('careerId');

$employeeDTO->organizationName = getParam('ORGANIZATION_NAME');
$employeeDTO->designationId = getParam('DESIGNATION_ID');
$employeeDTO->yearOfExperience = getParam('YEAR_OF_EXPERIENCE'); 
$employeeDTO->careerStartDate = date("Y-m-d", strtotime(getParam('CAREER_START_DATE')));
$employeeDTO->careerEndDate = date("Y-m-d", strtotime(getParam('CAREER_END_DATE')));
$employeeDTO->status = getParam('STATUS');
$mode = getParam('mode');

$emp = new employee_career();



if ($mode =='edit') {  
    $result = $emp->updateCareer($employeeDTO,$user_name);
} 
if ($mode =='new') {  
    $result = $emp->saveCareer($employeeDTO,$user_name);
} 

if ($mode =='remove') {  
    $removeCareerId = getParam('careerId');
    $result = $emp->removeCareer($removeCareerId);
} 

echo" <script>location.replace('index.php?mode=search&employeeId=$employeeId');</script>";

?>

