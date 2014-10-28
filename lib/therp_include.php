<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
//E_NOTICE for undefine varibale; 

include('standard_include.php');

$mode = getParam('mode');
$employeeId = get('employeeId');
$BranchDeptId = get('BranchDeptId');
$BranchDeptName = get('BranchDeptName');
$OfficeTypeId = get('OfficeType');
$userName = get('user_name');
$user_type = get('user_type');
$UserLevelId = get('UserLevelId');
$DB_NAME = get('DBNAME');
$DB_TYPE = get('DB_TYPE');
$Designation = get('DESIGNATION_ID');
$DesignationName = get('DESIGNATION_NAME');
$ProcessDeptId = get('ProcessDeptId');
$lineManagerId = get('lineManagerId');
$costCenterId = get("costCenterId");
?>