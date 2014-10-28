<?php
include '../lib/DbManager.php';
$val = getParam('val');

$branchDeptLis = $db->rs2array("SELECT BRANCH_DEPT_ID, BRANCH_DEPT_CODE, BRANCH_DEPT_NAME FROM branch_dept WHERE OFFICE_TYPE_ID='$val'");

combobox('BRANCH_DEPT_ID', $branchDeptLis, '', true);
?>


