<?php

include_once '../lib/DbManager.php';
include 'employee.php';
include 'employee_DTO.php';

$employeeDTO = new employeeDTO();

$employeeDTO->employeeId = getParam('EMPLOYEE_ID');
$employeeDTO->employeeNomineeInfoId = getParam('EMPLOYEE_NOMINEE_INFO_ID');
$employeeDTO->nomineeName = getParam('NOMINEE_NAME');
$employeeDTO->isFamilyMember= getParam('IS_FAMILY_MEMBER');
$employeeDTO->nomineeTypeId = getParam('NOMINEE_TYPE_ID');
$employeeDTO->relationship = getParam('RELATIONSHIP');
$employeeDTO->nomineeBirthday = date("Y-m-d", strtotime(getParam('DATE_OF_BIRTH')));
$employeeDTO->nomineePersentage = getParam('NOMINEE_PERCENTAGE');


$emp = new employee();


$count = $emp->countNominee($employeeDTO->employeeId);

if ($count > 0) {  
    $result = $emp->updateNominee($employeeDTO,$user_name);
} else {
    $result = $emp->saveNominee($employeeDTO,$user_name);
}

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>