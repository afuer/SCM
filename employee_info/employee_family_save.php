<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();

$employeeDTO->employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeFamilyInfoId = getParam('EMPLOYEE_FAMILY_INFO_ID');
$employeeDTO->familyMemberName = getParam('FAMILY_MEMBER_NAME');
$employeeDTO->familyRelationtype= getParam('FAMILY_RELATIONSHIP_TYPE');
$employeeDTO->isCblEmployee = getParam('IS_CBL_EMPLOYEE');
$employeeDTO->email = getParam('EMAIL');
$employeeDTO->contactPhoneNo = getParam('CONTACT_PHONE_NO');
$employeeDTO->profession = getParam('PROFESSION');
$employeeDTO->familyMemberDateOfBirth = date("Y-m-d", strtotime(getParam('DATE_OF_BIRTH')));






$emp = new employee();


$count = $emp->countFamily($employeeDTO->employeeId);

if ($count > 0) {  
    $result = $emp->updateFamily($employeeDTO,$user_name);
} else {
    $result = $emp->saveFamily($employeeDTO,$user_name);
}

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>