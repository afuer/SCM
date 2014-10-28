<?php

include '../lib/DbManager.php';
include ('employee.php');

$val = $_REQUEST['val'];
$employee = new employee();
$lineManager = $employee->GetDataRoute($val);
//echo $lineManager;
if ($lineManager > '0') {
    $routeList = json_decode($employee->routeCombo());
    echo '<td colspan="2"><span style="float:left;display:block; width: 97px;">Route:</span>' . combobox("ROUTE_ID", $routeList, $var->ROUTE_ID, true) . '</td>';
} else {
    echo '<td colspan="2"></td>';
}
?>
