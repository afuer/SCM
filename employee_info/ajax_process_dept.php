<?php

include '../lib/DbManager.php';
//include ('employee.php');

$processDeptId = $db->rs2array("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept");

$val = $_REQUEST['val'];
//$employee = new employee();
//$lineManager = $employee->GetDataRoute($val);
//echo $lineManager;
//if ($lineManager > '0') {
//$routeList = json_decode($employee->routeCombo());
combobox("ROUTE_ID", $processDeptId, $var->ROUTE_ID, true);
//} else {
//echo '<td colspan="2"></td>';
//}
?>
