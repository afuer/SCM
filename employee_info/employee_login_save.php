<?php

include_once '../lib/DbManager.php';
include 'employee.php';


$employeeId = getParam('EMPLOYEE_ID');
$passUserName = getParam('USER_NAME');
$password = getParam('USER_PASS');
$rePassword = getParam('RE_PASSWORD');
$levelId = getParam('USER_LEVEL_ID');
$routeId = getParam('ROUTE_ID');
$GET_USER_PASS = getParam('GET_USER_PASS');

$emp = new employee();

$passwordMd5 = md5($password);


 $count = $emp->countLogin($employeeId);
 
 $pass = $password;
 
if($password ==''){ $password = $GET_USER_PASS ; $rePassword = $GET_USER_PASS;  }

if ($password == $rePassword  ) { 
    if ($count > 0) {
        if ($pass == '') {
           $passwordMd5 = $GET_USER_PASS;
        }
        $result = $emp->updateLogin($employeeId, $passUserName, $passwordMd5, $levelId, $routeId, $user_name);
    } else { 
        $result = $emp->saveLogin($passUserName, $passwordMd5, $employeeId, $levelId, $routeId, $user_name);
    }
} else { 
    die('Password and confirm password are not matched.');
}
if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>