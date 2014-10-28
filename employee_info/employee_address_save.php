<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();

$employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeAddressId = getParam('EMPLOYEE_ADDRESS_ID');
$employeeDTO->presentAddress1 = getParam('PRESENT_ADDRESS1');
$employeeDTO->permanentAddress1 = getParam('PERMANENT_ADDRESS1');
$employeeDTO->presentAddress2 = getParam('PRESENT_ADDRESS2');
$employeeDTO->permanentAddress2 = getParam('PERMANENT_ADDRESS2');
$employeeDTO->presentThanaId = getParam('PRESENT_THANA_ID');
$employeeDTO->permanentThanaId = getParam('PERMANENT_THANA_ID');
$employeeDTO->presentPostalCode = getParam('PRESENT_POSTAL_CODE');
$employeeDTO->permanentPostalCode = getParam('PERMANENT_POSTAL_CODE');




$emp = new employee();
$count = $emp->countAddress($employeeDTO->employeeId);


if ($count > 0) {  
    $result = $emp->updateAddress($employeeDTO,$user_name);
} else {
    $result = $emp->saveAddress($employeeDTO,$user_name);
}



if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>