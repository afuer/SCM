<?php

include '../lib/DbManager.php';


$val = getParam('branchDeptId');

$var = find("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, s.SOL_CODE, s.SOL_NAME
FROM branch_dept bd
LEFT JOIN sol s ON s.SOL_ID=bd.SOL_ID
LEFT JOIN cost_center cc ON cc.DIVISION_ID=s.DIVISION_ID

WHERE bd.BRANCH_DEPT_ID='$val'");

if ($var->SOL_NAME == '') {
    echo 'N/A';
} else {
    echo $var->COST_CENTER_CODE . '->' . $var->COST_CENTER_NAME . '(' . $var->SOL_CODE . '->' . $var->SOL_CODE . ')';
}
?>
