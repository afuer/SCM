<?php

include 'include.php';

$val = getParam('val');


$DepartmentList = rs2array(query("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM department"));
$BranchList = rs2array(query("SELECT BRANCH_ID, BRANCH_NAME FROM branch"));
if ($val == 1) {
    comboBox('DepartmentID', $DepartmentList, '', TRUE, 'required');
} else {
    comboBox('BranchID', $BranchList, '', TRUE, 'required');
}
?>
