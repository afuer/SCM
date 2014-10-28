<?php

include_once '../lib/DbManager.php';
include 'employee.php';


$employeeId = getParam('EMPLOYEE_ID');
$employeeBankAccountInfoId = getParam('EMPLOYEE_BANK_ACCOUNT_INFO_ID');
$accountNumber = getParam('ACCOUNT_NUMBER');
$accountTypeId= getParam('ACCOUNT_TYPE_ID');
$branchId = getParam('BRANCH_ID');


$emp = new employee();


$count = $emp->countBank($employeeId);

if ($count > 0) {  
    $result = $emp->updateBankAccountInfo($employeeBankAccountInfoId,$accountNumber,$accountTypeId,$branchId,$user_name);
} else {
    $result = $emp->saveBankInfo($employeeId,$accountNumber,$accountTypeId,$branchId,$user_name);
}

if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>