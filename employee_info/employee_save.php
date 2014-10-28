<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();

//$employeeId = getParam('employeeId');

$employeeDTO->employeeId = getParam('employeeId');
$employeeDTO->firstName = getParam('FIRST_NAME');
$employeeDTO->middleName = getParam('MIDDLE_NAME');
$employeeDTO->lastName = getParam('LAST_NAME');
$employeeDTO->maritalStatusId = getParam('MARITAL_STATUS_ID');
$employeeDTO->ganderId = getParam('GANDER_ID');
$employeeDTO->nationalityId = getParam('NATIONALITY_ID');
$employeeDTO->dateOfBirth = getParam('DATE_OF_BIRTH');
$employeeDTO->religionId = getParam('RELIGION_ID');
$employeeDTO->nationalId = getParam('NATIONAL_ID');
$employeeDTO->passportNo = getParam('PASSPORT_NO');
$employeeDTO->passportIssueDate = getParam('PASSPORT_ISSUE_DATE');
$employeeDTO->passportExpireDate = getParam('PASSPORT_EXPIRE_DATE');
$employeeDTO->sellNo = getParam('SELL_NO');
$employeeDTO->emergencyPhoneNo = getParam('EMERGENCY_PHONE_NO');
$employeeDTO->homePhoneNo = getParam('HOME_PHONE_NO');
$employeeDTO->personalEmail = getParam('PERSONAL_EMAIL');
$employeeDTO->pabaxNo = getParam('PABAX_NO');
$employeeDTO->pabxExt = getParam('PABX_EXT');
$employeeDTO->referenceInfo = getParam('REFERENCE_INFO');


$emp = new employee();

$result = $emp->updateBasic($employeeDTO);


if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>