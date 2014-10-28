<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();



$employeeDTO->employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeOfficeinfoId = getParam('EMPLOYEE_OFFICE_INFO_ID');
$employeeDTO->employeeTypeId = getParam('EMPLOYEE_TYPE_ID');
$employeeDTO->supplierId = getParam('SUPPLIER_ID');
$employeeDTO->salary = getParam('SALARY');
$employeeDTO->lineManagerId = getParam('LINE_MANAGER_ID');
$employeeDTO->isReliever = getParam('IS_RELIEVER');
$employeeDTO->reliever_id = getParam('RELIEVER_EMP_ID');

$employeeDTO->job = getParam('JOB');
$employeeDTO->gradeId = getParam('GRADE_ID');
$employeeDTO->officeTypeId = getParam('OFFICE_TYPE_ID');
$employeeDTO->officePhoneNo = getParam('OFFICE_PHONE_NO');

$employeeDTO->joiningDate = getParam('JOINING_DATE');

$employeeDTO->assignmentCategoryId = getParam('ASSIGNMENT_CATEGORY_ID');
$employeeDTO->handiCapInfo = getParam('HANDICAP_INFO');
$employeeDTO->officeEmail = getParam('OFFICE_EMAIL');

$employeeDTO->retireMentDate = getParam('RETIREMENT_DATE');


$employeeDTO->location = getParam('LOCATION');
$employeeDTO->mobileBill = getParam('MOBILE_BILL');
$employeeDTO->internetBill = getParam('INTERNET_BILL');
$employeeDTO->othersBill = getParam('OTHERS_BILL');

$emp = new employee();
//$result = $emp->updateOfficeInfo($employeeDTO);

$count = $emp->countOffice($employeeDTO->employeeId);

if ($count > 0) {
    $result = $emp->updateOfficeInfo($employeeDTO, $user_name);
} else {
    $result = $emp->saveOffice($employeeDTO, $user_name);
}

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
//echo "<script>location.replace('index.php');</script>";
?>