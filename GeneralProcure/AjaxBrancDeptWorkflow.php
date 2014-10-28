<?php

include '../lib/DbManager.php';

$val = getParam('val');


$BranchDeptList = rs2array(query("SELECT BRANCH_DEPT_ID, BRANCH_DEPT_NAME FROM branch_dept WHERE OFFICE_TYPE_ID='$val'"));
comboBox('BranchDept', $BranchDeptList, '', TRUE, 'required');
?>
