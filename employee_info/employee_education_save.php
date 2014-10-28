<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();

$employeeDTO->employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeEducationInfoId = getParam('EMPLOYEE_EDUCATION_INFO_ID');
$employeeDTO->qualificationTitle = getParam('QUALIFICATION_TITLE');
$employeeDTO->major = getParam('MAJOR');
$employeeDTO->EducationPassingYear =  date("Y-m-d", strtotime(getParam('PASSING_YEAR')));
$employeeDTO->cgpaPercentage = getParam('CGPA_PERCENTAGE');
$employeeDTO->instituteName = getParam('INSTITUTE_NAME');
$employeeDTO->educationStatus = getParam('STATUS');
$employeeDTO->educationStartDate = date("Y-m-d", strtotime(getParam('START_DATE')));
$employeeDTO->educationEndDate = date("Y-m-d", strtotime(getParam('END_DATE')));
$employeeDTO->careerInfo = getParam('CAREER_INFO');






$emp = new employee();


$count = $emp->countEducation($employeeDTO->employeeId);

if ($count > 0) {  
    $result = $emp->updateEducation($employeeDTO,$user_name);
} else {
    $result = $emp->saveEducation($employeeDTO,$user_name);
}

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>